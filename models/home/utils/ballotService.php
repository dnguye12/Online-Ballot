<?php
    function getRemainingVote($ballot) {
        $vote = 0;
        foreach($ballot['voterList'] as $key => $value) {
          if($key == $_SESSION['email']) {
            $vote = $value;
          }  
        }
        return $vote;
    }
?>