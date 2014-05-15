<?php

class Datastore {

    public $file_loc = "q.json";


    public function __construct() {
        $this->__dummy();
    }

    public function update($data) {
        file_put_contents($this->file_loc, $data);
    }

    public function getJSON() {
        $contents = file_get_contents($this->file_loc);
        return $contents;
    }

    public function add($arr) {
        $arr['correct'] = trim($arr['correct']);
        if($arr['type'] == 'mcq') {
            $arr['choices'] = explode("|", trim($arr['opts']));
            if(!in_array($arr['correct'], $arr['choices']))
                $arr['choices'][] = $arr['correct'];
        }
        $arr['id'] = str_replace(".", "_", microtime(true).rand(0, 10000));
        unset($arr['opts']);
        $contents = $this->read();
        $contents[] = $arr;
        $this->update(json_encode($contents));
    }

    public function read($shuffle=FALSE) {
        $obs = json_decode($this->getJSON(), TRUE);
        if($shuffle)
        {
            shuffle($obs);
            $num = count($obs);
            for($i=0;$i<$num;$i++)
            {
                if($obs[$i]['type'] === 'mcq')
                {
                    shuffle($obs[$i]['choices']);
                }
            }
        }
        return $obs;
    }

    public function getResource($shuffle=FALSE)
    {
        $questions = $this->read($shuffle);
        $num = count($questions);
        $resource = array();
        for($i=0;$i<$num;$i++)
        {

            $resource = array_merge($resource, array(
                $questions[$i]['id'] => $questions[$i]
            ));
        }

        return $resource;
    }

    public function mark($arr) {
        $resource = $this->getResource();
        $correct = 0;
        $total = count($resource);
        foreach($arr as $q=>$a) {
               if(array_key_exists($q, $resource)) {
                   $iscorrect = trim(strtolower($a)) === trim(strtolower($resource[$q]['correct']));
                   $resource[$q]['userinput'] = $a;
                   $resource[$q]['result'] = "The answer : " . $a . " is " .
                       ($iscorrect ? " <span style='color:green;'>correct</span> " : " <span style='color:red'>wrong (".$resource[$q]['correct'].")</span> ");
                   $correct += $iscorrect ? 1 : 0;
               }
        }

        return array("resource" => $resource, "total" => $total, "correct" => $correct);
    }

    private function __dummy() {
        if(!file_exists($this->file_loc)) {
            file_put_contents($this->file_loc, "{}");
        }
    }

} 