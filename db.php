<?php

use Ramsey\Uuid\Uuid;
use Gregwar\Cache\Cache;

class DB {

  private $db = null;
  private static $instance = null;

  private function __construct () {
    $this->db = new Cache;
    $this->db->setCacheDirectory('../cache');
  }

  private function __clone () {
    // Stopping Clonning of Object
  }

  private function __wakeup () {
    // Stopping unserialize of object
  }

  public function addMeeting ($start_time, $end_time, $booked = false) {
    $meetings = $this->getMeetings();
    if (is_null($meeting)) {
      return NULL;
    }
    $id = Uuid::uuidv4()->toString();
    $meetings[$id] = array(
      id => $id,
      start_time => $start_time,
      end_time => $end_time,
      booked => $booked
    );
    $this->db->set('meetings', json_encode($meetings));
    return $id;
  }

  public function getMeetings ($id = null) {
    $meetings = $this->db->get('meetings');
    if (is_null($meetings)) {
      return NULL;
    }
    $meetings_obj = json_decode($meetings, true);
    if (is_null($id)) {
      return $meetings_obj;
    }
    return $meetings_obj[$id];
  }

  public function bookMeeting ($id) {
    $meetings = $this->getMeetings();
    if (is_null($meeting)) {
      return NULL;
    }
    $meetings[$id]['booked'] = true;
    $this->db->set('meetings', json_encode($meetings));
    return true;
  }

  public function setEmbedCode ($code) {
    $this->db->set('embed_code', $code);
  }

  public function getEmbedCode () {
    return $this->db->get('embed_code');
  }

  public static function connect () {
    // Check if instance is already exists
    if(self::$instance == null) {
        self::$instance = new DB();
    }
    return self::$instance;
  }

}
