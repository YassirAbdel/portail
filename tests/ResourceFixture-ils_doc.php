<?php
namespace App\DataFixtures;
ini_set('memory_limit', '-1');
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
        $stream = fopen('/Users/abdelmontet/Documents/http/portail/tests/ils_doc.csv', 'r');
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
                    //$manager->remove($result);
                    //$resource = new Resource();
                    $resource = $this->repository->findByDocRef($record['DOC_REF']);
                    
                }
            }
            if (isset($record['DOC_TYPE'])) {  
                $resource->setType($record['DOC_TYPE']);
            }
            if (isset($record['DOC_TITRE'])) {  
                    $resource->setTitle($record['DOC_TITRE']);
            }
            if (isset($record['DOC_AUTEUR'])) {
                    $resource->setAuteur($record['DOC_AUTEUR']);
            }
            if (isset($record['DOC_COLLECTION'])) {
                    $resource->setCollection($record['DOC_COLLECTION']);
            }
            if (isset($record['CND_DIVERS'])) {
                    $resource->setComment($record['CND_DIVERS']);
            }
            if (isset($record['DOC_EDITEUR'])) {
                    $resource->setEditeur($record['DOC_EDITEUR']);
            }
            if (isset($record['DOC_LIEU_EDIT'])) {
                    $resource->setEditeurlieu($record['DOC_LIEU_EDIT']);
            }
            if (isset($record['DOC_GEO'])) {
                   $resource->setGeo($record['DOC_GEO']);
            }
            if (isset($record['DOC_ISBN'])) {
                    $resource->setIsbn($record['DOC_ISBN']);
            }
            if (isset($record['DOC_DP'])) {
                    $resource->setAnneedit($record['DOC_DP']);
            }
            if (isset($record['DOC_LANGUE'])) {
                    $resource->setLang($record['DOC_LANGUE']);
            }
            if (isset($record['CND_OEUVRE'])) {
                    $resource->setOeuvre($record['CND_OEUVRE']);
            }
            if (isset($record['CND_COLL_AU'])) {
                    $resource->setOrganisme($record['CND_COLL_AU']);
            }
            if (isset($record['CND_DESC_MAT'])) {
                    $resource->setPagination($record['CND_DESC_MAT']);
            }
            if (isset($record['DOC_DE'])) {
                    $resource->setPerson($record['DOC_DE']);
            }
            if (isset($record['CND_TIT_F'])) {
                    $resource->setResp1($record['CND_TIT_F']);
            }
            
                    $resource->setRights(1);
            if (isset($record['DOC_DL'])) {
                    $resource->setTag($record['DOC_DL']);
            }
            if (isset($record['DOC_ANALYSE'])) {
                    $resource->setAnalyse($record['DOC_ANALYSE']);
            }
             $resource->setIdcadic($record['DOC_REF']);
             $resource->setOai(1);
             $resource->setLecteur('0');
             //$resource->setAllIndex($record['DOC_AUTEUR'].' '.$record['DOC_TITRE'].' '.$record['DOC_DL'].' '.$record['DOC_TYPE'].' '.$record['DOC_DE'].' '.$record['CND_TIT_F'].' '.$record['CND_OEUVRE']);
             $chacacters = array("|");
             $allIndex = $record['DOC_AUTEUR'].' '.$record['DOC_TITRE'].' '.$record['CND_TIT_F'].' '.$record['DOC_DL'].' '.$record['DOC_TYPE'].' '.$record['CND_COLL_AU'].' '.$record['DOC_DE'].' '.$record['CND_OEUVRE'];
             $allIndex = str_replace($chacacters, " ", $allIndex); 
             $resource->setAllIndex($allIndex);     
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