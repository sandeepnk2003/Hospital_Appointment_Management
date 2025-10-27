<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\DoctorModel;
use Dompdf\Dompdf;
use App\Models\PatientModel;
use App\Models\AppointmentModel;
use App\Models\userModel;
use App\Models\DoctorAvailabilityModel;
use App\Models\HospitalModel;
use App\Models\PaymentsModel;
use App\Models\PaymentsItemsModel;




class BillingController extends ResourceController
{
    public function addBilling($id){
       $appointmentmodel=new AppointmentModel();
       $data=$appointmentmodel
       ->select('appointments.*,users.username as doctor_name,doctors.consultation_fee,patients.name as patient_name')
       ->join('doctors','doctors.id=appointments.doctor_id')
       ->join('users','users.id=doctors.userId')
       ->join('patients','patients.id=appointments.patient_id')
       ->where('appointments.id',$id)
       ->first();
    //    dd($data);
       return view('billing/savebilling',$data);
    }
   public function save()
    {
        $paymentModel = new PaymentsModel();
        $paymentItemModel = new PaymentsItemsModel();
// dd($this->request->getPost('hospital_id'));
        // $db = \Config\Database::connect();
        // $db->transStart(); // Start transaction
// dd($this->request->getPost('patient_id'));
        // 1️⃣ Insert into payments table
        $paymentModel->save([
            'hospital_id'    =>$this->request->getPost('hospital_id'),
            'appointment_id' => $this->request->getPost('appointment_id'),
            'patient_id'     => $this->request->getPost('patient_id'),
            'doctor_id'      => $this->request->getPost('doctor_id'),
            'payment_mode'   => '',
            'total_amount'   => $this->request->getPost('total_amount'),
            'notes'          => $this->request->getPost('notes'),
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s'),
            'payment_status' => 'pending',
        ]);
//   dd($paymentData);
        //  $builder = $db->table('payments');
// $builder->insert($paymentData);

// 👉 Get inserted ID
// $payment_id = $db->insertID();
$insertedId = $paymentModel->insertID();
// dd($insertedId);
        // 2️⃣ Insert payment items
        $items = $this->request->getPost('items');
        if ($items && is_array($items)) {
            foreach ($items as $item) {
                $paymentItemModel->save([
                    'payment_id' =>$insertedId,
                    'item_name'  => $item['item_name'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'total'      => $item['quantity'] * $item['price'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                // $paymentItemModel->insert($itemData);
            }
        }

        // $db->transComplete();

        // 3️⃣ Redirect based on payment mode
        $paymentMode = $this->request->getPost('payment_mode');
        $total = $this->request->getPost('total_amount');

        // if ($paymentMode === 'upi') {
            // Replace with your PhonePe UPI ID
//            if ($paymentMode === 'upi') {
//     $upiID = "yourphonepeid@ybl"; 
//     $upiURL = "upi://pay?pa={8050750015@ybl}&pn=Hospital&am={1000}&cu=INR&tn=Hospital+Payment";

//     return view('upi_redirect', [
//         'upiURL' => $upiURL,
//         'payment_id' => $payment_id
//     ]);
// }

        // }

        // Otherwise go directly to success page
        return redirect()->to('/doctor_dashboard');
    }

    // ✅ Step 2: Payment success page (manual verification)
    public function success($id)
    {   
        $paymentModel = new PaymentsModel();
        $payment = $paymentModel->find($id);
    //   dd($payment);
        return view('billing/paymentSuccess', ['payment' => $payment]);
    }

    // ✅ Step 3: Mark payment as completed
    public function markCompleted($id)
    {
        $paymentModel = new PaymentsModel();
        $paymentModel->update($id, [
            'payment_mode'=>$this->request->getPost('payment_mode'),
            'payment_status' => 'completed',
            'payment_date' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/payments')
                         ->with('success', 'Payment marked as completed successfully.');
    }
public function paymentManagement(){
    $paymentModel=new PaymentsModel();
    $data['payments']=$paymentModel
    ->select('payments.*,patients.name as patient_name,users.username as doctor_name')
    ->join('patients','patients.id=payments.patient_id')
    ->join('doctors','doctors.id=payments.doctor_id')
    ->join('users','users.id=doctors.userId')
    ->orderBy("FIELD(payments.payment_status, 'pending', 'completed')")  // <--- single string
    ->orderBy('payments.created_at', 'DESC')      
    ->findAll();
    // dd($data);
    return view('billing/payment_dashboard',$data);
}
 public function slip($paymentId = null)
    {
        if (!$paymentId) {
            return redirect()->back()->with('error', 'Missing payment ID');
        }

        $paymentModel = new PaymentsModel();
        $itemModel = new PaymentsItemsModel();

        // 1️⃣ Fetch payment details (joined with hospital/patient)
        $payment =(object) $paymentModel
            ->select('
                payments.id AS payment_id, 
                payments.total_amount, 
                payments.payment_mode, 
                payments.payment_status, 
                payments.notes, 
                payments.created_at,
                patients.name AS patient_name, 
                patients.phone AS patient_contact, 
                hospitals.hospital_name, 
                hospitals.hospital_contact,
                hospitals.hospital_email
            ')
            ->join('patients', 'patients.id = payments.patient_id', 'left')
            ->join('hospitals', 'hospitals.id = payments.hospital_id', 'left')
            ->where('payments.id', $paymentId)
            ->first();

        if (!$payment) {
            return redirect()->back()->with('error', 'Payment not found');
        }

        // 2️⃣ Fetch payment items
        $items = $itemModel
            ->where('payment_id', $paymentId)
            ->findAll();

        // 3️⃣ Prepare data for view
        $data = [
            'payment' => $payment,
            'items' => $items,
        ];

        // 4️⃣ Render view
        $html = view('billing/billingpdf', $data);

        // 5️⃣ Generate PDF
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // 6️⃣ Stream PDF for download
        $fileName = 'payment_slip_' . $payment->payment_id . '.pdf';
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $fileName . '"')
            ->setBody($dompdf->output());
    }

}
