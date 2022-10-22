<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Response;
use App\Models\UserTransaction;
use App\Models\Transaction;
class TestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
       //
    }



    public function AddData()
    {
        $Json_transactions = file_get_contents('E:\xampp\htdocs\test\Assigiment/transactions.json');
        $Json_users = file_get_contents('E:\xampp\htdocs\test\Assigiment/users.json');
        $arrTransactions = json_decode($Json_transactions, true);
        $arrUsers = json_decode($Json_users, true);
      
        foreach ($arrTransactions['transactions'] as  $objTransaction) {
         
           DB::table('transactions')->insert([
            'paidAmount' => $objTransaction['paidAmount'],
            'currency' => $objTransaction['Currency'],
            'parentEmail' => $objTransaction['parentEmail'],
            'statusCode' => $objTransaction['statusCode'],
            'payment_date' => $objTransaction['paymentDate'],
            'parent_identification' => $objTransaction['parentIdentification'],
           ]);
        }


        foreach ($arrUsers['users'] as  $objUser) {
            $change_date = str_replace('/', '-', $objUser['created_at']);
            $convert = date('Y-m-d',strtotime($change_date));
           
           DB::table('user_transactions')->insert([
            'balance' => $objUser['balance'],
            'currency' => $objUser['currency'],
            'email' => $objUser['email'],
            'created_at' => $convert,
            'id' => $objUser['id'],
           ]);
        }
        return Response::json('Successful Added');
    }



    public function DeleteData()
    {
       DB::table('transactions')->truncate();
       DB::table('user_transactions')->truncate();
       return Response::json('Successful Deleted');
    }


    public function FilterTransactions()
    {
        $statusCode = "";
        $currency = "";
        $paidAmount = "";
        $date_from = "";
        $date_to = "";
         
        $arrTransaction = Transaction::with('Users')->get();

        $arrTransaction = Transaction::join('user_transactions', 'transactions.parentEmail', '=', 'user_transactions.email');


        if(!empty($_GET['statusCode'])){
        $statusCode = $_GET['statusCode'];
        $arrTransaction = $arrTransaction->where('statusCode',$statusCode);
        }

        if(!empty($_GET['currency'])){
        $currency = $_GET['currency'];
        $arrTransaction = $arrTransaction->where('currency',$currency);
        }


        if(!empty($_GET['paidAmount'])){
        $paidAmount = $_GET['paidAmount'];
        $arrTransaction = $arrTransaction->where('paidAmount',$paidAmount);
        }

        if(!empty($_GET['date_from'])){
        $date_from = $_GET['date_from'];
        $arrTransaction = $arrTransaction->where('payment_date','>=',$date_from);
        }

        if(!empty($_GET['date_to'])){
        $date_to = $_GET['date_to'];
        $arrTransaction = $arrTransaction->where('payment_date','<=',$date_to);
        }
        $arrTransaction = $arrTransaction->get();
        return Response::json($arrTransaction);
    }



    public function FilterUserTransactions()
    {
        $balance = "";
        $currency = "";
        $email = "";
        $date_from = "";
        $date_to = "";
         
        $arrUserTransaction = UserTransaction::with('Transactions')->get();
        if(!empty($_GET['balance'])){
        $balance = $_GET['balance'];
        $arrUserTransaction = $arrUserTransaction->where('balance',$balance);
        }


        if(!empty($_GET['currency'])){
        $currency = $_GET['currency'];
        $arrUserTransaction = $arrUserTransaction->where('currency',$currency);
        }

         if(!empty($_GET['email'])){
        $email = $_GET['email'];
        $arrUserTransaction = $arrUserTransaction->where('email',$email);
        }

        if(!empty($_GET['date_from'])){
        $date_from = $_GET['date_from'];
        $arrUserTransaction = $arrUserTransaction->where('created_at','>=',$date_from);
        }

        if(!empty($_GET['date_to'])){
        $date_to = $_GET['date_to'];
        $arrUserTransaction = $arrUserTransaction->where('created_at','<=',$date_to);
        }
        return Response::json($arrUserTransaction);
    }

}
