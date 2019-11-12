<?php 
use Restserver \Libraries\REST_Controller ; 
Class Sparepart extends REST_Controller{

    public function __construct(){ 
        header('Access-Control-Allow-Origin: *'); 
        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE"); 
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding"); 
        parent::__construct(); $this->load->model('SparepartModel'); $this->load->library('form_validation'); 
    } 
    public function index_get(){ 
        return $this->returnData($this->db->get('spareparts')->result(), false); 
    } 
    public function index_post($id = null){ 
        $validation = $this->form_validation; 
        $rule = $this->SparepartModel->rules(); 
        if($id == null){ 
            array_push($rule,[ 
                'field' => 'name', 
                'label' => 'name', 
                'rules' => 'required' 
            ], 
            [ 
                'field' => 'merk', 
                'label' => 'merk', 
                'rules' => 'required' 
            ] ,
            [ 
                'field' => 'amount', 
                'label' => 'amount', 
                'rules' => 'required|numeric' 
            ]); 
        } else{
            array_push($rule,[ 
                'field' => 'name', 
                'label' => 'name', 
                'rules' => 'required' 
            ], 
            [ 
                'field' => 'merk', 
                'label' => 'merk', 
                'rules' => 'required' 
            ] ,
            [ 
                'field' => 'amount', 
                'label' => 'amount', 
                'rules' => 'required|numeric' 
            ]);
        }

        $validation->set_rules($rule); 
        if (!$validation->run()) { 
            return $this->returnData($this->form_validation->error_array(), true); 
        } 
        $sparepart = new SparepartData(); 
        $sparepart->name = $this->post('name'); 
        $sparepart->merk = $this->post('merk'); 
        $sparepart->amount = $this->post('amount'); 
        $sparepart->created_at = $this->post('created_at');
        if($id == null){ 
            $response = $this->SparepartModel->store($sparepart);
        }else{ 
            $response = $this->SparepartModel->update($sparepart,$id); 
        } 
        return $this->returnData($response['msg'], $response['error']); 
    } 
    public function index_delete($id = null){ 
        if($id == null){ 
            return $this->returnData('Parameter Id Tidak Ditemukan', true); 
        } 
        $response = $this->SparepartModel->destroy($id); 
        return $this->returnData($response['msg'], $response['error']); 
    } 
    public function returnData($msg,$error){ 
        $response['error']=$error; 
        $response['message']=$msg; 
        return $this->response($response); 
    } 
} 
Class SparepartData{ 
    public $name; 
    public $merk; 
    public $amount; 
    public $created_at; 
}