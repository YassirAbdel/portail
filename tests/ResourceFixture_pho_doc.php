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
        $stream = fopen('/Users/abdelmontet/Documents/http/portail/tests/PHO_DOC_PHOTOS.csv', 'r');
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
            if (isset($record['CND_TIT_F'])) { 
                $resource ->setResp1($record['CND_TIT_F']);
            }
            if (isset($record['DOC_LANGUE'])) { 
                $resource->setLang($record['DOC_LANGUE']);
            }
            if (isset($record['DOC_COMMENT'])) { 
                $resource->setComment($record['DOC_COMMENT']);
            }
            //if (isset($record['CND_URL'])) {
                //$resource->setUrlDoc($record['CND_URL']);
            //}
            if (isset($record['CND_OEUVRE'])) {
                $resource->setOeuvre($record['CND_OEUVRE']);
            }
            if (isset($record['DOC_AUTEURSEC'])) {
                $resource->setAuteurS($record['DOC_AUTEURSEC']);
            }
            if (isset($record['DOC_AUTMORAL'])) {
                $resource->setAuteurM($record['DOC_AUTMORAL']);
            }
            if (isset($record['PHO_RESP_EDIT'])) {
                $resource->setResEdit($record['PHO_RESP_EDIT']);
            }
            if (isset($record['DOC_DP'])) {
                $resource->setAnneedit($record['DOC_DP']);
            }
            if (isset($record['DOC_DP_STAT'])) {
                $resource->setAnneeS(intval($record['DOC_DP_STAT']));
            }
            if (isset($record['DOC_DEE'])) {
                $resource->setTag($record['DOC_DEE']);
            }
            if (isset($record['PHO_PERSON'])) {
                $resource->setPerson($record['PHO_PERSON']);
            }
            if (isset($record['DOC_GEO'])) {
                $resource->setGeo($record['DOC_GEO']);
            }
            if (isset($record['PHO_HISTORI'])) {
                $resource->setPeHisto($record['PHO_HISTORI']);
            }
            if (isset($record['CND_COLL_AU'])) {
                $resource->setOrganisme($record['CND_COLL_AU']);
            }
            if (isset($record['DOC_COLLECTION'])) {
                $resource->setCollection($record['DOC_COLLECTION']);
            }
            if (isset($record['PHO_ORIGINE'])) {
                $resource->setOrigDoc($record['PHO_ORIGINE']);
            }
            if (isset($record['PHO_COPYRIGHT'])) {
                $resource->setCopyR($record['PHO_COPYRIGHT']);
            }
            if (isset($record['PHO_DROIT_IMG'])) {
                $resource->setRightsA($record['PHO_DROIT_IMG']);
            }
            if (isset($record['DOC_SUP'])) {
                $resource->setSupport($record['DOC_SUP']);
            }
            if (isset($record['PHO_COULEUR'])) {
                $resource->setCouleur($record['PHO_COULEUR']);
            }
            if (isset($record['PHO_FORMAT'])) {
                $resource->setFormat($record['PHO_FORMAT']);
            }
            if (isset($record['PHO_FORMFILE'])) {
                $resource->setFormFile($record['PHO_FORMFILE']);
            }
            if (isset($record['CND_DUREE'])) {
                $resource->setDuree($record['CND_DUREE']);
            }
            if (isset($record['PHO_NB_DOC'])) {
                $resource->setNbFiles($record['PHO_NB_DOC']);
            }
            if (isset($record['DOC_COTE'])) {
                $resource->setCote($record['DOC_COTE']);
            }
            if (isset($record['PHO_NUMCD'])) {
                $resource->setSupNum($record['PHO_NUMCD']);
            }
            if (isset($record['PHO_LOC_CD'])) {
                $resource->setLocaSupnum($record['PHO_LOC_CD']);
            }
            if (isset($record['CND_COTE_P'])) {
                $resource->setCoteNum($record['CND_COTE_P']);
            }
            if (isset($record['PHO_LOCDIAORI'])) {
                $resource->setLocaSup($record['PHO_LOCDIAORI']);
            }
            if (isset($record['AUD_REF'])) {
                $resource->setAudio($record['AUD_REF']);
            }
            if (isset($record['CND_VIDEO_REF1'])) {
                $resource->setVideo($record['CND_VIDEO_REF1']);
            }
            if (isset($record['CND_URL'])) {
                $resource->setUrlDoc($record['CND_URL']);
            }
            if (isset($record['DOC_ATTACHE'])) {
               $resource->setPdf($record['DOC_ATTACHE']);
            }
            if (isset($record['IMG_REF'])) {
                $resource->setImg($record['IMG_REF']);
            }
            if (isset($record['DOC_REF'])) {
                $resource->setIdcadic($record['DOC_REF']);

            }  
            $resource->setRights(1);
            $resource->setOai(1);
            $resource->setAnalyse(1);
            $resource->setLecteur('4');
                    
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