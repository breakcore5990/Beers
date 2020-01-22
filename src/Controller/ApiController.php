<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp;

class ApiController extends AbstractController
{
    private $beers;
    private $client;

    function __construct()
    {
        $this->client = $client = new GuzzleHttp\Client();
    }

    /**
     * @Route("/beers", name="index",methods={"GET","HEAD"})
     * @param  Request  $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $response = null;
            $this->beers = json_decode($this->punkQuery($request->get('food')));
            if (is_array($this->beers) || is_object($this->beers)) {
                foreach ($this->beers as $beer) {
                    $response[] = [
                        'id' => $beer->id,
                        'name' => $beer->name,
                        'description' => $beer->description,
                        'food' => $beer->food_pairing,
                    ];
                }
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("beers/view", name="view",methods={"GET","HEAD"})
     * @param  Request  $request
     * @return JsonResponse
     */
    public function view(Request $request)
    {
        try {
            $response = null;
            $this->beers = json_decode($this->punkQuery($request->get('food')));
            if (is_array($this->beers) || is_object($this->beers)) {
                foreach ($this->beers as $beer) {
                    $response[] = [
                        'id' => $beer->id,
                        'name' => $beer->name,
                        'description' => $beer->description,
                        'first_brewed' => $beer->first_brewed,
                        'image_url' => $beer->image_url,
                        'food' => $beer->food_pairing,
                    ];
                }
            }
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }

        return new JsonResponse($response);
    }

    /**
     * @param $query
     * @return string
     */
    public function punkQuery($query)
    {
        $query = strtr($query, ' ', '_');
        $url = "https://api.punkapi.com/v2/beers";
        try {
            $request = $this->client->request('GET', $url, [
                'query' => [$query]
            ]);
            $response = $request->getBody()->getContents();
        } catch (\Exception $e) {
            $response = $e->getMessage();
        }
        return $response;
    }


}
