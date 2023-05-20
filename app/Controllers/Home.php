<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data['friends'] = $this->friend->select('*, friends.id as idfriend, groupGender.name as jenisKelamin, friends.name as fullname')
        ->join('groupGender', 'groupGender.id = friends.genderID')->get()->getResult();
        $data['male_count'] = count(array_filter($data['friends'], function($friend) {
            return $friend->jenisKelamin === 'Male';
        }));

        $data['female_count'] = count($data['friends']) - $data['male_count'];

        $data['below_19_count'] = count(array_filter($data['friends'], function($friend) {
            return $friend->age <= 19;
        }));
        
        $data['above_20_count'] = count($data['friends']) - $data['below_19_count'];
        
        $data['groupGender'] = $this->gender->get()->getResult();
        return view('Home/index', $data);
    }

    public function saveTeman(){
        $rules = [
            'name' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'Nama harus diisi.',
                    'max_length' => 'Nama tidak boleh lebih dari 50 karakter.'
                ]
            ],
            'gender' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jenis kelamin harus diisi.',
                ]
            ],
            'age' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Usia harus diisi.',
                    'numeric' => 'Usia harus berupa angka.'
                ]
            ]
        ];
    
        // validate input
        if (!$this->validate($rules)) {
            $html = $this->isvalid->listErrors();
            $oneline = preg_replace('/\s+/', ' ', $html);
            $this->sesi->setFlashdata('validation', $oneline);
            return redirect()->back();
        }

        $dataTeman = [
            'name' => $this->request->getVar('name'),
            'genderID' => $this->request->getVar('gender'),
            'age' => $this->request->getVar('age')
        ];
        try {
            $this->friend->simpan($dataTeman);
            return redirect()->to('/');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function editData(){
        $id = $this->request->getVar('idFriend');
        $dataTeman = [
            'name' => $this->request->getVar('name'),
            'genderID' => $this->request->getVar('gender'),
            'age' => $this->request->getVar('age')
        ];
        try {
            $cekUpdate = $this->friend->update($id, $dataTeman);
            $res = [
                "status" => 200,
                "message" => "Succes update",
                "data" => $cekUpdate
            ];
            echo json_encode($res);
        } catch (\Exception $e) {
            echo json_encode($e->getMessage());
        }
    }
}
