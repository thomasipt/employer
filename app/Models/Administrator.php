<?php
    namespace App\Models;

    use App\Models\BaseModel;
    use Config\Database;
    use App\Libraries\Tabel;

    class Administrator extends BaseModel{
        public $tableId     =   'id';

        public function getAdministrator($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $administrator              =   $this->getData($t->administrator, $id, $options, $this->tableId);

            return $administrator;
        }
        public function authenticationProcess($username, $password){
            $options    =   [
                'singleRow'     =>  true,
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
            
            $administratorBuilder   =   $db->table($tabel->administrator);
            if(!empty($id)){
                $administratorBuilder->where($this->tableId, $id);
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