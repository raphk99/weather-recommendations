<?php
// src/Controller/RecommendationController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WeatherService;
use Symfony\Component\Serializer\SerializerInterface;

class RecommendationController extends AbstractController
{
    private $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * @Route("/api/recommendations", name="recommendations", methods={"POST"})
     */
    #[Route('/api/recommendations', methods: ['POST'])]
    public function create(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $data = $this->toJson($request->getContent());
        if (null === $data) {
            return $this->json(['status' => 'Error', 'message' => 'Invalid JSON'], JsonResponse::HTTP_BAD_REQUEST);
        }
        $city = $data['city'] ?? '';
        $date = $data['date'] ?? 'today';
        // print 'aaaaa';
        // dd($serializer->deserialize($request->getContent(), RecommendationController::class, 'json'));
        //print $request->getContent();

        // Fetch the weather forecast
        $forecast = $this->weatherService->getForecast($city, $date);
        if ([] === $forecast) {
            return $this->json(['status' => 'Error', 'message' => 'City not found'], JsonResponse::HTTP_BAD_REQUEST);
        }

        // Determine the temperature and recommendation category
        $temp = $forecast['day']['avgtemp_c'];
        print($temp);
        $category = $this->getCategoryByTemperature($temp);

        // Example product recommendations (replace with actual data fetching)!!!!!!!!
        $products = [
            'pull' => [
                ['id' => '1', 'name' => 'Warm Sweater', 'price' => 30.00],
                ['id' => '2', 'name' => 'Thick Cardigan', 'price' => 35.00],
                ['id' => '3', 'name' => 'Fleece Pullover', 'price' => 28.00]
            ],
            'sweat' => [
                ['id' => '4', 'name' => 'Cozy Hoodie', 'price' => 25.00],
                ['id' => '5', 'name' => 'Light Sweatshirt', 'price' => 22.00],
                ['id' => '6', 'name' => 'Sporty Sweater', 'price' => 27.00]
            ],
            'tshirt' => [
                ['id' => '7', 'name' => 'Cool T-Shirt', 'price' => 20.00],
                ['id' => '8', 'name' => 'Tank Top', 'price' => 15.00],
                ['id' => '9', 'name' => 'Cotton Blouse', 'price' => 18.00]
            ]
        ];

        return new JsonResponse([
            'products' => $products[$category],
            'weather' => [
                'city' => $city,
                'is' => $this->getWeatherDescription($temp),
                'date' => $date
            ]
        ]);
    }

    private function getCategoryByTemperature(float $temp): string
    {
        if ($temp < 10) {
            return 'pull';
        } elseif ($temp < 20) {
            return 'sweat';
        }
        return 'tshirt';
    }

    private function getWeatherDescription(float $temp): string
    {
        if ($temp < 10) {
            return 'cold';
        } elseif ($temp < 20) {
            return 'mild';
        }
        return 'hot';
    }

    private function toJson(string $string): array {
        //VALIDATE THE STRING
        // print(count(explode(':', $string)));
        //print_r(explode(':', $string));
        if (count(explode(':', $string)) == 4) {
            $city = preg_replace('/[^a-zA-Z]/', '', explode(',',explode(':', $string)[2])[0]);
            $date = preg_replace('/[^a-zA-Z]/', '', explode(':', $string)[3]);
            return ['city' => $city, 'date' => $date];
        }
        else if (count(explode(':', $string)) == 3) {
            $city = preg_replace('/[^a-zA-Z]/', '', explode(':', $string)[2]);
            return ['city' => $city];
        }
        return [];
    }
}

?>