<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Airport;

class MainController extends Controller
{

    // Web default endpoint
    // Ikke helt ferdig, prioriter API for frontend og App til App kommunikasjon
    public function index() {

       $data = $this->getFlights('TRD');
       $flights = $data->flights;
       $json = json_decode(json_encode($flights));

       return view('main', compact('flights'));
    }


    // returner JSON data til API
    public function api(string $flyplass) {

      // filtrer string flyplass
      $flyplass = strip_tags($flyplass);
      $data = $this->getFlights($flyplass);

      $flights = $data->flights;

      // $json = json_decode(json_encode($flights));
      // print_r($json);
      return response()->json($flights);

    }


    // Default airport OSL
    public function getFlights(string $airport = OSL) {

      $context  = stream_context_create(array('http' => array('header' => 'Accept: application/xml')));
      $url = 'https://flydata.avinor.no/XmlFeed.asp?direction=D&airport='.$airport;
      $xml = file_get_contents($url, false, $context);
      $xml = simplexml_load_string($xml);
      return $xml;
    }


    // Ta i mot input fra form og filtrer det
    public function setFlyplass(Request $request) {

      $raw = $request->flyplass;
      $flyplass = filter_var($raw, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
      $this->flyplass = $flyplass;

    }


    // If airports in DB, return DB query. Else fetch from external API and save to DB.
    public function getAirportNames() {

      $count = Airport::get()->count();
      if ($count > 1) {
        $airports = Airport::get();
        return response()->json($airports);
      } else {
        $this->externalGetAirportNames();
        $airports = Airport::get();
        return response()->json($airports);
      }



    }

    // Only run if we don't have populated the local DB with airport names
    private function externalGetAirportNames() {
      $handle = curl_init();

      $url = "https://flydata.avinor.no/airportNames.asp?airport";
      curl_setopt($handle, CURLOPT_URL, $url);
      curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
      $output = curl_exec($handle);
      curl_close($handle);

      $airports = new \SimpleXMLElement($output);
      foreach($airports->children() as $airport) {
        $airport = Airport::create([
          'code' => $airport['code'],
          'name' => $airport['name']
        ]);
        $airport->save();
      }
    }
}
