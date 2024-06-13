<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\JediniceMjere;
use App\Entity\Valute;
use App\Repository\ArtikliRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route('/api', name: 'api_')]
class ProjectController extends AbstractController
{
    #[Route('/sumprices', name: 'sumprice_index', methods: ['get'])]
    public function sumprices(ArtikliRepository $artikliRepository): JsonResponse
    {

        $artikli = $artikliRepository->findAll();

        $kraticaValute = "1";
        $nazivValute = "";

        if ($artikli != null) {

            $kraticaValute = $artikli[0]->getValute()->getKratica();
            $nazivValute = $artikli[0]->getValute()->getNaziv();
        }

        $ukupnaCijena = $artikliRepository->getSumOfAllArticles();

        return $this->json(['ukupnacijena' => $ukupnaCijena, "kraticaValute" => $kraticaValute, 
            "nazivValute" => $nazivValute]);
    } # end function


    #[Route('/sumorderedproducts/{date}', name: 'sumorderedproduct_index', methods: ['get'])]
    public function sumorderedproducts(ArtikliRepository $artikliRepository, string $date): JsonResponse
    {

        $artikli = $artikliRepository->findAll();

        $kraticaValute = "1";
        $nazivValute = "";

        if ($artikli != null) {

            $kraticaValute = $artikli[0]->getValute()->getKratica();
            $nazivValute = $artikli[0]->getValute()->getNaziv();
        }

        $result = $artikliRepository->getSumOfAllArticlesByDate($date, "654321");


        return $this->json($result);

    } # end function


    #[Route('/create', name: 'project_create', methods: ['post'])]
    public function create(Request $request, ArtikliRepository $artikliRepository): JsonResponse
    {

        if ($request->request->get("naziv") == null)
            return $this->json(["message" => "Nije unesena vrijednost za 'naziv'"]);

 #       if ($request->request->get("stanjeNaSkladistu") == null)
 #           return $this->json(["message" => "Nije unesena vrijednost za 'stanjeNaSkladistu'"]);

            if ($request->request->get("jedinicaMjereId") == null)
            return $this->json(["message" => "Nije unesena vrijednost za 'jedinicaMjereId'"]);

        if ($request->request->get("valuteId") == null)
            return $this->json(["message" => "Nije unesena vrijednost za 'valuteId'"]);



        $arrArtikl = [];

        $arrArtikl['jmId'] = $request->request->get('jedinicaMjereId');
        $arrArtikl['valuteId'] = $request->request->get('valuteId');
        $arrArtikl['naziv'] = $request->request->get('naziv');
        $arrArtikl['stanjeNaSkladistu'] = $request->request->get('stanjeNaSkladistu');
        $arrArtikl['cijena'] = $request->request->get('cijena');
        $arrArtikl['trazenoStanje'] = $request->request->get('trazenoStanje');
        $arrArtikl['cijenaUNabavi'] = $request->request->get('cijenaUNabavi');
        $arrArtikl['krajnjiRokNabave'] = $request->request->get('krajnjiRokNabave');


        $result = $artikliRepository->createArtikl($arrArtikl);

        return $this->json($result);

    } # end function

    #[Route("/update", name: "project_update", methods: ["put"])]
    public function update(ArtikliRepository $artikliRepository, Request $request): JsonResponse
    {

        $arrArtikli = [];

#        $arrArtikli["naziv"] = $request->request->get("naziv");
        $naziv = $request->request->get("naziv");

        if ($request->request->get("umanjitiZa") == null)
            return $this->json(["message" => "Nije unesena vrijednost za 'umanjitiZa'"]);

        $umanjitiZa = (float) $request->request->get("umanjitiZa");

        $arrResult = $artikliRepository->updateStanjeNaSkladistu($umanjitiZa, $naziv);

        return $this->json($arrResult);
    } # end function

} 

?>
