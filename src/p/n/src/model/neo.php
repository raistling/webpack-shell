<?php
global $API_KEY, $token;
$token = $API_KEY;

if(isset($_SESSION['token'])){
    $token = $_SESSION['token'];
}


class Neo
{
    private $date;
    private $data;
    private $url;

    public function __construct($date)
    {
        global $token;
        $this->date = $date;
        $this->url = 'https://api.nasa.gov/neo/rest/v1/feed?start_date=' . $date . '&end_date=' . $date . '&api_key=' . $token;
        $this->getContent();
    }


    public function getData()
    {
        return $this->data;
    }

    public function getAsteroidCount()
    {
        return $this->data->element_count;
    }

    public function getAsteroidAlerts()
    {
        $asteroidsAlert = [];

        $asteroids = $this->getData()->near_earth_objects->{$this->date};
        foreach ($asteroids as $asteroid) {
            if ($asteroid->is_potentially_hazardous_asteroid) {
                $asteroidsAlert[] = $asteroid;
            }
        }
        return $asteroidsAlert;
    }


    public function getDate()
    {
        return $this->date;
    }

    private function getContent()
    {
        $content = file_get_contents($this->url);
        $this->data = json_decode($content);
    }

}