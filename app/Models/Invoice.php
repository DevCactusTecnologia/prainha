<?php

namespace App\Models;

use App\Models\Appointment\Appointment;
use App\Models\InvoiceDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'patient_id',
        'payment_mode',
        'payment_status',
        'invoice_date',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    // -- DoctorController
    // $invoices = Invoice::with('user')
    //     ->where('invoices.created_by', $doctor->id)
    //     ->orderByDesc('id')
    //     ->get();

    // $invoices = Invoice::with('user')
    //     ->where('doctor_id', $doctor_id)
    //     ->paginate($limit, '*', 'invoice');

    // $revenue = DB::select(
    //     'SELECT SUM(amount) AS total FROM invoice_details, invoices 
    //     WHERE invoices.id = invoice_details.invoice_id AND created_by = ?', [$doctor->id]
    // );
    
    // $pending_bill = DB::select(
    //     "SELECT COUNT(*) AS total FROM invoices 
    //     WHERE invoices.payment_status = 'Unpaid' AND created_by = ?", [$doctor->id]
    // );

    // -- BiomedicalController
    // $invoices = Invoice::with('user')
    //     ->where(function ($re) use ($user_id, $biomedicals_doctor_id) {
    //         $re->whereIN('doctor_id', $biomedicals_doctor_id);
    //         $re->orWhere('created_by', $user_id);
    //     })->paginate($this->limit, '*', 'invoice');

    // $revenue = DB::select('SELECT SUM(amount) AS total FROM invoice_details, invoices WHERE invoices.id = invoice_details.invoice_id AND created_by = ?', [$biomedical->id]);
        
    // $pending_bill = Invoice::where(['payment_status' => 'Unpaid'])
    //     ->where(function ($re) use ($user_id, $biomedicals_doctor_id) {
    //         $re->whereIN('doctor_id', $biomedicals_doctor_id);
    //         $re->orWhere('created_by', $user_id);
    //     })->count();

    // -- ReceptionistController

    // $revenue = DB::select('SELECT SUM(amount) AS total FROM invoice_details, invoices WHERE invoices.id = invoice_details.invoice_id AND created_by = ?', [$receptionist->id]);
    
    // $pending_bill = Invoice::where(['payment_status' => 'Unpaid'])
    //     ->where(function ($re) use ($user_id, $receptionists_doctor_id) {
    //         $re->whereIN('doctor_id', $receptionists_doctor_id);
    //         $re->orWhere('created_by', $user_id);
    //     })->count();

    // $invoices = Invoice::with('user')
    // ->where(function ($re) use ($user_id, $receptionists_doctor_id) {
    //     $re->whereIN('doctor_id', $receptionists_doctor_id);
    //     $re->orWhere('created_by', $user_id);
    // })->paginate($this->limit, '*', 'invoice');

    // RELATIONSHIPS

    function invoice_detail()
    {
        return $this->hasMany(InvoiceDetail::class)->where('is_deleted', 0);
    }

    function user()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    function patient()
    {
        return $this->hasOne(User::class, 'id', 'patient_id');
    }

    function doctor()
    {
        return $this->hasOne(User::class, 'id', 'doctor_id');
    }

    function appointment()
    {
        return $this->hasOne(Appointment::class, 'id', 'appointment_id');
    }
}
