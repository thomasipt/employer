<?php
    namespace App\Models;

    use Config\Database;

    #Models
    use App\Models\BaseModel;
    use App\Models\Transaksi;

    #Libraries
    use App\Libraries\Tabel;

    use Exception;

    class Mitra extends BaseModel{
        public $emailVerification_verified  =   'verified';
        public $emailVerification;

        public $approvement_approved    =   'approved';
        public $approvement_rejected    =   'rejected';
        public $approvement;

        public $passwordDefault     =   '123456';

        public function __construct(){
            parent::__construct();
            
            $approvement        =   [$this->approvement_approved, $this->approvement_rejected];
            $this->approvement  =   $approvement;

            $emailVerification          =   [$this->emailVerification_verified];
            $this->emailVerification    =   $emailVerification;
        }

        public function getMitra($id = null, $options = null){
            $d  =   new Database();
            $t  =   new Tabel();

            $this->connectedDatabase    =   $d->default;
            $mitra                      =   $this->getData($t->mitra, $id, $options);

            return $mitra;
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

            $mitraAuthenticationProcess  =   $this->getMitra(null, $options);
            return $mitraAuthenticationProcess;
        }
        public function saveMitra($id = null, $dataMitra = null){
            $database   =   new Database();
            $tabel      =   new Tabel();
            $db         =   $database->connect($database->default);
            
            $mitraBuilder   =   $db->table($tabel->mitra);
            if(!empty($id)){
                $mitraBuilder->where('id', $id);
                $saveMitra  =   $mitraBuilder->update($dataMitra);
            }else{                
                $saveMitra  =   $mitraBuilder->insert($dataMitra);
                $id         =   $db->insertID();
            }

            return ($saveMitra)? $id : false;
        }
        public function getPaketAktif($idMitra, $returnTransaksi = false){
            $paketAktif     =   null;

            $detailMitra    =   $this->getMitra($idMitra, ['select' => 'id']);
            if(empty($detailMitra)){
                throw new Exception('Tidak ditemukan mitra dengan pengenal '.$idMitra.'!');
            }

            helper('CustomDate');
            $transaksi      =   new Transaksi();

            $now    =   rightNow();

            $options        =   [
                'singleRow' =>  true,
                'where'     =>  [
                    'approvement'   =>  $transaksi->approvement_approved,
                    'mitra'         =>  $idMitra,
                    'stackedBy'     =>  null
                ]
            ];
            $detailPaketAktif   =   $transaksi->getTransaksi(null, $options);
            if(empty($detailPaketAktif)){
                throw new Exception('Tidak ada paket aktif!');
            }

            $berlakuMulai   =   $detailPaketAktif['berlakuMulai'];
            $berlakuSampai  =   $detailPaketAktif['berlakuSampai'];
            if(!($now >= $berlakuMulai && $now <= $berlakuSampai)){
                throw new Exception('Masa berlaku dari '.formattedDateTime($berlakuMulai).' s/d '.formattedDateTime($berlakuSampai).'!');
            }

            if($returnTransaksi){
                return $detailPaketAktif;
            }

            $paketAktif     =   $detailPaketAktif['paket'];
            return $paketAktif;
        }
        public function isApproved($idMitra){
            $detailMitra    =   $this->getMitra($idMitra, ['select' => 'approvement']);
            if(empty($detailMitra)){
                throw new Exception('Tidak ditemukan mitra dengan pengenal '.$idMitra.'!');
            }

            $approvement    =   $detailMitra['approvement'];
            if(is_null($approvement)){
                throw new Exception('Pendaftaran anda belum disetujui!');
            }

            $isActive       =   $approvement == $this->approvement_approved;
            return $isActive;
        }
        public function getJumlahMitraButuhVerifikasi(){
            $options    =   [
                'singleRow' =>  true,
                'select'    =>  'count(id) as jumlah',
            ];
            $getJumlahMitra     =   $this->getMitraNeedApprove($options);

            $jumlahMitra    =   (!empty($getJumlahMitra))? $getJumlahMitra['jumlah'] : 0;
            return $jumlahMitra;
        }
        public function getMitraNeedApprove($additionOptions = null){
            $options    =   [
                'where'     =>  [
                    'approvement'   =>  null
                ]
            ];
            
            if(!empty($additionOptions)){
                if(is_array($additionOptions)){
                    $options    =   array_merge($options, $additionOptions);
                }
            }

            $getMitra       =   $this->getMitra(null, $options);
            return $getMitra;
        }
    }
?>