<?php
    namespace App\Models;

    use App\Models\BaseModel;
    use Config\Database;
    use App\Libraries\Tabel;

    class Administrator extends BaseModel{
        public function getAdministrator($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $administrator              =   $this->getData($t->administrator, $id, $options);

            return $administrator;
        }
        public function authenticationProcess($username, $password){
            $options    =   [
                'singleRow'     =>  true,
                'select'        =>  'id, nama, username',
                'whereGroup'    =>  [
                    'operator'  =>  $this->whereGroupOperator_or,
                    'where'     =>  [
                        ['username' =>  $username],
                        ['email'    =>  $username],
                        ['telepon'  =>  $username]
                    ]
                ],
                'where' =>  [
                    'password'  =>  md5($password)
                ]
            ];

            $administrator  =   $this->getAdministrator(null, $options);
            return $administrator;
        }
        public function saveAdministrator($id = null, $dataAdministrator = null){
            helper('CustomDate');

            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $administratorBuilder   =   $db->table($tabel->administratorLog);
            if(!empty($id)){
                $administratorBuilder->where('id', $id);
                $saveAdministrator  =   $administratorBuilder->update($dataAdministrator);
            }else{
                $dataAdministrator['createdAt']   =   rightNow();
                
                $saveAdministrator  =   $administratorBuilder->insert($dataAdministrator);
                $id                 =   $db->insertID();
            }

            return ($saveAdministrator)? $id : false;
        }
    }
?>