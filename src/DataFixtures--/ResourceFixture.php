<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Resource;
use League\Csv\Reader;
use League\Csv\Statement;
use App\Repository\ResourceRepository;
use Symfony\Flex\Unpack\Result;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;


class ResourceFixture extends Fixture
{
    private $repository;
    private $em;
    
    public function __construct(ResourceRepository $repository, ObjectManager $em) 
    {
        $this->repository = $repository;
        $this->em = $em;
        
    }
    
    public function load(ObjectManager $manager)
    {
        $stream = fopen('/Applications/MAMP/htdocs/portail/tests/import-cadic.csv', 'r');
        $cvs = Reader::createFromStream($stream);
        $cvs->setDelimiter(';');
        $cvs->setHeaderOffset(0);
        
        $stmt = new Statement();
        $stmt->offset(10)->limit(25);
      
        $records = $stmt->process($cvs);
        
        // Define the size of records
        $size = count($records);
        $batchSize = 20;
        $i = 1;
        
        
        foreach ($records as $record) {
            
            // On vérifie si la ressource existe
            $compteur = $this->repository->compteByIdcadic($record['DOC_REF']);
            
            // On récupère la ressource
            $results = $this->repository->findByIdcadic($record['DOC_REF']);
            //dump($result);
            
            // Si la ressource n'exite pas
            if ($compteur < 1) {
               
                $resource = new Resource();
            }
            
            // Si elle exite, on la supprime et on crée une autre avec les bons infos
            else 
            {
                foreach ($results as $result)  {
                    $manager->remove($result);
                    $resource = new Resource();
                }
            }
                $resource->setType($record['DOC_TYPE'])
                        ->setTitle($record['DOC_TITRE'])
                        ->setAuteur($record['DOC_AUTEUR'])
                        ->setCollection($record['DOC_COLLECTION'])
                        ->setComment($record['CND_DIVERS'])
                        ->setEditeur($record['DOC_EDITEUR'])
                        ->setEditeurlieu($record['DOC_LIEU_EDIT'])
                        ->setGeo($record['DOC_GEO'])
                        ->setIsbn($record['DOC_ISBN'])
                        ->setAnneedit($record['DOC_DP'])
                        ->setLang($record['DOC_LANGUE'])
                        ->setOeuvre($record['CND_OEUVRE'])
                        ->setOrganisme($record['CND_COLL_AU'])
                        ->setPagination($record['CND_DESC_MAT'])
                        ->setPerson($record['DOC_DE'])
                        ->setResp1($record['CND_TIT_F'])
                        ->setRights(1)
                        ->setTag($record['DOC_DL'])
                        ->setAnalyse($record['DOC_ANALYSE'])
                        ->setOai(1)
                        ->setIdcadic($record['DOC_REF'])
                        ;
                $manager->persist($resource);
                
                // Each 20 resources persisted we flush everything
                if (($i % $batchSize) === 0) {
                    
                    $manager->flush();
                    // Detaches all objects from Doctrine for memory save
                    $manager->clear();
                }
                
                $i++;
                
             }
        
        $manager->flush();
        $manager->clear();
        
        
     }
       
}
