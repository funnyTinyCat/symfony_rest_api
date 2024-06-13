<?php

namespace App\Repository;

use App\Entity\Artikli;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Artikli>
 */
class ArtikliRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Artikli::class);
    }

    //    /**
    //     * @return Artikli[] Returns an array of Artikli objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Artikli
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getSumOfAllArticles(): ?float
    {

        $sumOfAllValues = $this->findAll();

        $sum = 0;

        foreach($sumOfAllValues as $value) {

            $sum += $value->getStanjeNaSkladistu();
        }

        return $sum;
    } # end function

    public function getSumOfAllArticlesByDate(string $date, string $sifra): array # ?float
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(

            'SELECT sum(a.trazenoStanje * a.cijenaUNabavi) cijenaPotrazivanjaPoDatumu
            FROM App\Entity\Artikli a 
            where a.krajnjiRokNabave <= :date  '
        )->setParameter('date', $date);

        $sum = $query->getResult();

# get tecaj:
        $conn = $entityManager->getConnection();

        $sql = '
            SELECT t.tecaj, v.kratica FROM valute as v
            left join tecaj_prema_euro as t on v.id = t.valute_id   
            where v.sifra = :sifra
            ';

        $tecaj = $conn->executeQuery($sql, ['sifra' => $sifra]);

        $tmp = 0;

        $tecaj = $tecaj->fetchAllAssociative();

        $arrTecaj = $tecaj[0];
     #   $kratica = $arrTecaj[1];
        $sum = $sum[0];

        $finalSum = ($arrTecaj["tecaj"] * $sum["cijenaPotrazivanjaPoDatumu"]);

        return ["suma" => $finalSum, "kratica" => $arrTecaj["kratica"]];
    } # end function


    public function checkIfArticleExists(EntityManager $entityManager, string $articleName, string $tableName): bool
    {

        $query = $entityManager->createQuery(

            'SELECT a.naziv 
            FROM ' . $tableName . ' a
            where a.naziv = :naziv '
        )->setParameter('naziv', $articleName);

        $result = $query->getResult();

        if($result != null){
            
            return true;
        } # end if

        return false;
    } # end function


    public function checkIfExists(EntityManager $entityManager, int $id, string $tableName): bool
    {

        $query = $entityManager->createQuery(

            'SELECT a.id 
            FROM ' .  $tableName  . ' a
            where a.id = :id '
        )->setParameter('id', $id);

        $result = $query->getResult();

        if($result == null){
            
            return true;
        } # end if

        return false;
    } # end function

    public function createArtikl(array $arrArtikl): array
    {
        $entityManager = $this->getEntityManager();

        # check valute:
        $flag = $this->checkIfExists($entityManager, $arrArtikl["valuteId"], "App\Entity\Valute");

        if($flag == true){
            
            $message = "Ne postoji valuta za id: " . $arrArtikl['valuteId'];
            return ["message" => $message];
        } # end if
  
        # check jedinice mjere:
        $flag = $this->checkIfExists($entityManager, $arrArtikl["jmId"], "App\Entity\JediniceMjere");

        if($flag == true){
            
            $message = "Ne postoji jedinica mjere za id: " . $arrArtikl['jmId'];
            return ["message" => $message];
        } # end if

        # check is there article with the same name:
        $flag = $this->checkIfArticleExists($entityManager, $arrArtikl["naziv"], "App\Entity\Artikli");

        if($flag == true){
            
            $message = "Artikal sa istim nazivom već postoji - " . $arrArtikl['naziv'];
            return ["message" => $message];
        } # end if

        # create artikl:
         $conn = $this->getEntityManager()->getConnection();

        $sql = '
            insert into artikli(jedinica_mjere_id, valute_id, naziv, stanje_na_skladistu, cijena, 
            trazeno_stanje, cijena_unabavi, krajnji_rok_nabave)
                values(:jmId, :valuteId, :naziv, :stanjeNaSkladistu, :cijena, :trazenoStanje,
                    :cijenaUNabavi, :krajnjiRokNabave)
            ';

        $resultSet = $conn->executeQuery($sql, ['jmId' => $arrArtikl["jmId"], 'valuteId' => $arrArtikl["valuteId"],
            'naziv' => $arrArtikl["naziv"], 
            'stanjeNaSkladistu' => ($arrArtikl["stanjeNaSkladistu"] == "") ? 0 : $arrArtikl["stanjeNaSkladistu"],
            'cijena' => ($arrArtikl["cijena"] == "") ? null : $arrArtikl["cijena"], 
            'trazenoStanje' => ($arrArtikl["trazenoStanje"] == "") ? null : $arrArtikl["trazenoStanje"], 
            'cijenaUNabavi' => ($arrArtikl["cijenaUNabavi"] == "") ? null : $arrArtikl["cijenaUNabavi"], 
            'krajnjiRokNabave' => ($arrArtikl["krajnjiRokNabave"] == "") ? null : $arrArtikl["krajnjiRokNabave"]
        ]);

        if ($resultSet == null) {

            return ["message" => "Zapisivanje artikla nije uspjelo!"];
        }

        $sql = '
            select * from artikli order by id desc limit 1
        ';

        $resultSet = $conn->executeQuery($sql);

        $tmp = $resultSet->fetchAllAssociative();
        $tmp = $tmp[0];
      
        return ["id" => $tmp["id"],
            'jedinica_mjere_id' => $tmp["jedinica_mjere_id"], 'valute_id' => $tmp["valute_id"],
            'naziv' => $tmp["naziv"], 'stanje_na_skladistu' => $tmp["stanje_na_skladistu"], 
            'cijena' => ($tmp["cijena"] == "") ? "-" : $tmp["cijena"],
            'trazeno_stanje' => ($tmp["trazeno_stanje"] == "") ? "-" : $tmp["trazeno_stanje"], 
            'cijena_unabavi' => ($tmp["cijena_unabavi"] == "") ? "-" : $tmp["cijena_unabavi"], 
            'krajnji_rok_nabave' => ($tmp["krajnji_rok_nabave"] == "") ? "-" : $tmp["krajnji_rok_nabave"] ]; 
        
    } # end function


    public function checkStanjeNaSkladistu(EntityManager $entityManager, float $umanjitiZa, string $articleName): array
    {

        $query = $entityManager->createQuery(

            'SELECT a.stanjeNaSkladistu
            FROM App\Entity\Artikli a
            where a.naziv = :naziv '
        )->setParameter('naziv', $articleName);

        $result = $query->getResult();

        if($result == null){
            
            return ["flag" => true, "stanjeNaSkladistu" => null];
        } # end if 

        $result = $result[0];

        if ($result["stanjeNaSkladistu"] < $umanjitiZa) {

            return ["flag" => true, "stanjeNaSkladistu" => $result["stanjeNaSkladistu"]];
        }
    #    var_dump($result["stanjeNaSkladistu"]);

        return ["flag" => false, "stanjeNaSkladistu" => $result["stanjeNaSkladistu"]];
    } # end function

    public function updateStanjeNaSkladistu(float $umanjitiZa, string $articleName): array
    {

        $entityManager = $this->getEntityManager();

        $flag = $this->checkIfArticleExists($entityManager, $articleName, "App\Entity\Artikli");

        if(!($flag == true)) {

            return ["message" => "Nema zapisa za naziv artikla - " . $articleName ."."];
        }

        # provjeriti da li je stanje na skladištu veće od broja za koje se treba umanjiti?
        $arrResult = $this->checkStanjeNaSkladistu($entityManager, $umanjitiZa, $articleName);

        if(($arrResult["flag"] == true) ) {

            if ($arrResult["stanjeNaSkladistu"] != null) {
                return ["message" => "Stanje na skladištu: " . $arrResult["stanjeNaSkladistu"] 
                    . " je manje od količine koja se traži: " 
                    . $umanjitiZa . "."];
            } else {

                return ["message" => "Nešto nije u redu!"];
                
            }

        } # end if

        # izračunati ostatak stanja na skladištu?
        $novoStanjeNaSkladistu = $arrResult["stanjeNaSkladistu"] - $umanjitiZa;

        # update stanje na skladistu:
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            update artikli
                set stanje_na_skladistu = :novoStanjeNaSkladistu 
                where naziv = :articleName 
            ';

        $resultSet = $conn->executeQuery($sql, ["novoStanjeNaSkladistu" => $novoStanjeNaSkladistu,
            "articleName" => $articleName]);

        if ($resultSet == null) {

            return ["message" => "Ažuriranje artikla nije uspjelo!"];
        }

        $sql = '
            select * from artikli where naziv = :naziv
        ';

        $resultSet = $conn->executeQuery($sql, ["naziv" => $articleName]);

        $resultSet = $resultSet->fetchAllAssociative();
        $arrResult = $resultSet[0];
      
        return ["id" => $arrResult["id"],
            'jedinica_mjere_id' =>  $arrResult["jedinica_mjere_id"], 'valute_id' => $arrResult["valute_id"],
            'naziv' => $arrResult["naziv"], 'stanje_na_skladistu' => $arrResult["stanje_na_skladistu"], 
            'cijena' => ($arrResult["cijena"] == "") ? "-" : $arrResult["cijena"], 
            'trazeno_stanje' => ($arrResult["trazeno_stanje"] == "") ? "-" : $arrResult["trazeno_stanje"], 
            'cijena_unabavi' => ($arrResult["cijena_unabavi"] == "") ? "-" : $arrResult["cijena_unabavi"], 
            'krajnji_rok_nabave' => ($arrResult["krajnji_rok_nabave"] == "") ? "-" : $arrResult["krajnji_rok_nabave"] 
        ]; 

    } # end function

}
