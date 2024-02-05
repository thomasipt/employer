<?php
    namespace App\Models;

    use CodeIgniter\Database\BaseBuilder;
    use CodeIgniter\Model;
    use Config\Database;

    #Library
    use App\Libraries\FileUpload;

    class BaseModel extends Model{
        public $connectedDatabase;
        
        public $whereGroupOperator_or   =   'or';
        public $likeGroupOperator_or    =   'or';
        
        private function sqlOptions(BaseBuilder $builder, $options = null){
            $singleRow  =   false;
            $orderBy    =   null;
            $groupBy    =   null;

            if(!is_null($options)){
                if(is_array($options)){
                    if(array_key_exists('select', $options)){
                        $select     =   $options['select'];
                        $builder->select($select);
                    }
                    if(array_key_exists('join', $options)){
                        $join   =   $options['join'];

                        if(is_array($join)){
                            if(count($join) >= 1){
                                foreach($join as $joinItem){
                                    if(array_key_exists('table', $joinItem) && array_key_exists('condition', $joinItem)){
                                        $tableToJoin    =   $joinItem['table'];
                                        $condition      =   $joinItem['condition'];
                                        $type           =   (array_key_exists('type', $joinItem))? $joinItem['type'] : 'left';
            
                                        $builder->join($tableToJoin, $condition, $type);
                                    }
                                }
                            }
                        }
                    }
                    if(array_key_exists('having', $options)){
                        $having  =   $options['having'];
                        $builder->having($having);
                    }
                    if(array_key_exists('where', $options)){
                        $where  =   $options['where'];
                        $builder->where($where);
                    }
                    if(array_key_exists('whereGroup', $options)){
                        $whereGroup     =   $options['whereGroup'];
                        if(is_array($whereGroup)){

                            if(array_key_exists('operator', $whereGroup) && array_key_exists('where', $whereGroup)){
                                $operator       =   $whereGroup['operator'];
                                $whereCondition =   $whereGroup['where'];   
                                
                                if($operator === $this->whereGroupOperator_or){
                                    if(is_array($whereCondition)){
                                        if(count($whereCondition) >= 1){
                                            $builder->groupStart();
                                            foreach($whereCondition as $index => $wC){
                                                if($index == 0){
                                                    $builder->where($wC);
                                                }else{
                                                    $builder->orWhere($wC);
                                                }
                                            }
                                            $builder->groupEnd();
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                    if(array_key_exists('where_not_in', $options)){
                        $whereNotIn  =   $options['where_not_in'];
                        $whereNotInColumn   =   $whereNotIn['column'];
                        $whereNotInValues   =   $whereNotIn['values'];

                        $builder->whereNotIn($whereNotInColumn, $whereNotInValues);
                    }
                    if(array_key_exists('limit', $options)){
                        $limit              =   $options['limit'];
                        $limitStartFrom     =   (array_key_exists('limitStartFrom', $options))? $options['limitStartFrom'] : false;

                        if($limitStartFrom !== false){
                            $builder->limit($limit, $limitStartFrom);
                        }else{
                            $builder->limit($limit);
                        }
                    }
                    if(array_key_exists('where_in', $options)){
                        $whereInColumn  =   $options['where_in']['column'];
                        $whereInValues  =   $options['where_in']['values'];
                        
                        $builder->whereIn($whereInColumn, $whereInValues);
                    }
                    if(array_key_exists('like', $options)){
                        $like  =   $options['like'];
                        if(is_array($like)){
                            if(array_key_exists('column', $like) && array_key_exists('value', $like)){
                                $column     =   $like['column'];
                                $value      =   $like['value'];
                                
                                $builder->groupStart();
                                if(is_array($column)){
                                    foreach($column as $indexData => $kolom){
                                        if($indexData == 0){
                                            $builder->like($kolom, $value);
                                        }else{
                                            $builder->orLike($kolom, $value);
                                        }
                                    }
                                }
                                if(is_string($column)){
                                    $builder->like($column, $value);
                                }
                                $builder->groupEnd();
                            }
                        }
                    }
                    if(array_key_exists('likeGroup', $options)){
                        $likeGroup     =   $options['likeGroup'];
                        if(is_array($likeGroup)){

                            if(array_key_exists('operator', $likeGroup) && array_key_exists('like', $likeGroup)){
                                $operator       =   $likeGroup['operator'];
                                $likeCondition  =   $likeGroup['like'];   
                                
                                if($operator === $this->likeGroupOperator_or){
                                    if(is_array($likeCondition)){
                                        if(count($likeCondition) >= 1){
                                            $builder->groupStart();
                                            foreach($likeCondition as $index => $lC){
                                                if($index == 0){
                                                    $builder->like($lC);
                                                }else{
                                                    $builder->orLike($lC);
                                                }
                                            }
                                            $builder->groupEnd();
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if(array_key_exists('order_by', $options)){
                        $orderBy    =   $options['order_by'];
                        if(is_array($orderBy)){
                            $orderByColumn          =   $orderBy['column'];
                            $orderByOrientation     =   $orderBy['orientation'];
                            
                            $builder->orderBy($orderByColumn, $orderByOrientation);
                        }
                    }
                    if(array_key_exists('singleRow', $options)){
                        $singleRow   =   $options['singleRow'];
                    }
                    if(array_key_exists('group_by', $options)){
                        $groupBy    =   $options['group_by'];
                    }
                }
            }

            $returnedData   =   [
                'singleRow' =>  $singleRow,
                'orderBy'   =>  $orderBy,
                'groupBy'   =>  $groupBy
            ];
            return $returnedData;
        }
        public function getData($tabel, $id = null, $options = null, $idColumn = 'id'){
            $connectedDatabase  =   'default';
            if(!empty($this->connectedDatabase)){
                $connectedDatabase  =   $this->connectedDatabase;
            }
            
            $db         =   Database::connect($connectedDatabase);
            $builder    =   $db->table($tabel.' pT'); #pt = primary table

            $sqlOptions =   $this->sqlOptions($builder, $options);
            $singleRow  =   $sqlOptions['singleRow'];
            $orderBy    =   $sqlOptions['orderBy'];
            $groupBy    =   $sqlOptions['groupBy'];

            if(!empty($id)){
                $builder->where('pT.'.$idColumn, $id);
            }
            if(is_null($orderBy)){
                $builder->orderBy('pT.'.$idColumn, 'desc');
            }
            if(!is_null($groupBy)){
                $builder->groupBy($groupBy);
            }

            $getData    =   $builder->get();

            if(!empty($id)){
                $data   =   $getData->getRowArray();
            }else{
                $data   =   $getData->getResultArray();

                if($singleRow){
                    if(count($data) >= 1){
                        $data   =   $data[0];
                    }else{
                        $data   =   null;
                    }
                }
            }
            
            return $data;
        }
        protected function whereOptionsGenerator($queryStringParameter, $queryStringFields, $queryStringFieldsOperator = null){
            $whereOptions   =   [];

            foreach($queryStringParameter as $qs_key => $qs_value){
                if(array_key_exists($qs_key, $queryStringFields)){
                    $fieldName  =   $queryStringFields[$qs_key];

                    if(is_string($fieldName)){
                        if(!is_null($queryStringFieldsOperator)){
                            if(is_array($queryStringFieldsOperator)){
                                if(count($queryStringFieldsOperator) >= 1){
                                    if(array_key_exists($qs_key, $queryStringFieldsOperator)){
                                        $operator               =   $queryStringFieldsOperator[$qs_key];

                                        $fieldName  =   $fieldName.' '.$operator;
                                    }
                                }
                            }
                        }
                        $whereOptions[$fieldName]   =   $qs_value;
                    }
                    if(is_array($fieldName)){
                        if(count($fieldName) >= 1){
                            foreach($fieldName as $fieldIndex => $fieldName_item){
                                $fieldNameAndOperator   =   $fieldName_item;

                                if(!is_null($queryStringFieldsOperator)){
                                    if(is_array($queryStringFieldsOperator)){
                                        if(count($queryStringFieldsOperator) >= 1){
                                            if(array_key_exists($qs_key, $queryStringFieldsOperator)){
                                                $operator               =   $queryStringFieldsOperator[$qs_key];

                                                if(is_array($operator)){
                                                    if(count($operator) >= 1){
                                                        $operatorField      =   $fieldName[$fieldIndex];
                                                        $operatorOperator   =   $operator[$fieldIndex];
                                                        $fieldNameAndOperator   =   $operatorField.' '.$operatorOperator;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                $whereOptions[$fieldNameAndOperator]  =   $qs_value;
                            }
                        }
                    }
                }
            }
            return $whereOptions;
        }
        protected function likeGroupOptionsGenerator($queryStringParameter, $queryStringFields, $queryStringFieldsOperator = null){
            $likeGroupOptions   =   [];

            foreach($queryStringParameter as $qs_key => $qs_value){
                if(array_key_exists($qs_key, $queryStringFields)){
                    $fieldName  =   $queryStringFields[$qs_key];
                    $fieldValue =   $qs_value;
                    
                    $likeItem   =   [$fieldName => $fieldValue];
                    array_push($likeGroupOptions, $likeItem);
                }
            }
            
            return $likeGroupOptions;
        }
        public function getGroupedData($sourceTable, $entityGroup, $options = null, $idColumn = 'id'){
            $option        =   [
                'select'    =>  $entityGroup,
                'group_by'  =>  $entityGroup
            ];

            if(!empty($options)){
                if(is_array($options)){
                    $option    =   array_merge($option, $options);
                }
            }

            $listGroupedData    =   $this->getData($sourceTable, null, $option, $idColumn);

            $groupedData    =   [];
            foreach($listGroupedData as $groupedDataItem){
                array_push($groupedData, $groupedDataItem[$entityGroup]);
            }
            return $groupedData;
        }
        public function getDataFromGroupedData($sourceTable, $column, $value, $options = null, $idColumn = 'id'){
            $option    =   [
                'where' =>  [$column => $value]
            ];

            if(!empty($options)){
                if(is_array($options)){
                    $option     =   array_merge($options);
                }
            }

            $data   =   $this->getData($sourceTable, null, $option, $idColumn);
            return $data;
        }
        public function deleteUploadGambar($path, $fileName){
            $fileUpload     =   new FileUpload();
            $defaultImage   =   $fileUpload->defaultImage;

            if(!in_array($fileName, $defaultImage)){
                $filePath   =   $path.'/'.$fileName;
                if(file_exists($filePath)){
                    unlink($filePath);
                }
            }
        } 
    }
?>