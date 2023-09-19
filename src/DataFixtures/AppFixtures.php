<?php

namespace App\DataFixtures;

use App\Entity\Blog;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\Evenement;
use App\Entity\Image;
use App\Entity\Jouet;
use App\Entity\Liste;
use App\Entity\Mecanisme;
use App\Entity\Note;
use App\Entity\Personne;
use App\Entity\Pret;
use App\Entity\Recompense;
use App\Entity\Theme;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    const ROLES = [['ROLE_ADMIN'],['ROLE_MEMBER'],['ROLE_MEMBER']];
    const ROLES_PERSONNE =[['Auteur'],['Illustrateur'], ['Auteur','Illustrateur']];
    const LANGUES = ['Français','Anglais'];
    const AGE = ['1 à 3 ans','2 à 4 ans' ,'10 ans et plus'];
    const DURATION = ['20 à 30 min','1  à 2 h' ,'10 à 20 min'];
    const CIBLE = ['Enfants','Adolescent' ];

    public function __construct(private UserPasswordHasherInterface $hasher, private SluggerInterface $slugger)
    {
    }


    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = [];
        $tag = [];
        $mecanisme = [];
        $theme = [];
        $recompense = [];

        for ($u = 0; $u < 3; $u++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'test'))
                ->setNom($faker->lastName())
                ->setPrenom($faker->firstName())
                ->setUsername($faker->userName())
                ->setRoles(AppFixtures::ROLES[$u])
                ->setTelephone($faker->phoneNumber())
                ->setBloquer(false);
            $manager->persist($user);
            $users[] = $user;
        }

        for ($t = 0; $t < 2; $t++) {
            $imageRec = (new Image())->setFilePath("default.jpg");
           

            $cat = new Categorie();
            $cat->setNom($faker->word());
            $cat->setSlug($this->slugger->slug(strtolower($cat->getNom())));
            $manager->persist($cat);
            $tag[] = $cat;


            $mec = new Mecanisme();
            $mec->setNom($faker->word());
            $mec->setSlug($this->slugger->slug(strtolower($mec->getNom())));
            $manager->persist($mec);
            $mecanisme[] = $mec;

            $tem = new Theme();
            $tem->setNom($faker->word());
            $tem->setSlug($this->slugger->slug(strtolower($tem->getNom())));
            $manager->persist($tem);
            $theme[] = $tem;


            $rec = new Recompense();
            $rec->setNom($faker->word());
            $tem->setSlug($this->slugger->slug(strtolower($tem->getNom())));
            $manager->persist($rec);
            $imageRec->setSlug($this->slugger->slug(strtolower($rec->getNom())))->setRecompense($rec);
            $manager->persist($imageRec);
            $recompense[] = $rec;

        }  
      

        for ($j = 0; $j < 5; $j++) {
            $imagePers = (new Image())->setFilePath("default.jpg");
            $imageJouet = (new Image())->setFilePath("default.jpg");
            $video = (new Video())->setLien($faker->url());
            $jouet = new Jouet();
            $jouet->setNom($faker->word())
                ->setDescription($faker->text())
                ->setContenue($faker->text())
                ->setSlug($this->slugger->slug(strtolower($jouet->getNom())))
                ->setLangues(AppFixtures::LANGUES)
                ->setAge(AppFixtures::AGE[random_int(0,2)])
                ->setJouers(random_int(0,10))
                ->setDuration(AppFixtures::DURATION[random_int(0,2)])
                ->setCodeBar(strval(random_int(10000000000,88888888888)))
                ->setCible(AppFixtures::CIBLE)
                ->setIsExtension(false)
                ->setDisponible(true)
                ->setCategorie($tag[random_int(0,1)])
                ->addMecanisme($mecanisme[random_int(0,1)])
                ->addTheme($theme[random_int(0,1)])
                ->addUsersWish($users[1])
                ->setAnnee(random_int(1999,2023));
                
              $manager->persist($jouet);

              $pret = new Pret();
              $pret->setJouet($jouet)
                  ->setUser($users[random_int(1,2)])
                  ->setObservation($faker->text(50))
                  ->setRetourner(false)
                  ->setDateDebut(new \DateTimeImmutable)
                  ->setDateFin(new \DateTimeImmutable);
              $manager->persist($pret);
              

              $video->setJouet($jouet);
              $manager->persist($video);

              $imageJouet->setSlug($this->slugger->slug(strtolower($jouet->getNom())))->setJouet($jouet);
              $manager->persist($imageJouet);


            $note = new Note();
            $note->setNote(floatval(random_int(0,20)))
                ->setJouet($jouet)
                ->setUser($users[1]);
            $manager->persist($note);



            $per = new Personne();
            $per->setNom($faker->name());
            $per->setFacebook($faker->url());
            $per->setTwitter($faker->url());
            $per->addJouet($jouet);
            $per->setRoles(AppFixtures::ROLES_PERSONNE[random_int(0,2)]);
            $manager->persist($per);

            $imagePers->setSlug($this->slugger->slug(strtolower($rec->getNom())))->setPersonne($per);
            $manager->persist($imagePers);


           
            $list  =  new Liste();
            $list->setNom($faker->word())
                ->addJouet($jouet)
                ->setUser($users[1]);
            $manager->persist($list);

            for ($c = 0; $c < 5; $c++) {
                $commentaire =  new Commentaire();
                $commentaire->setContenue($faker->text(50))
                            ->setJouet($jouet);
                $manager->persist($commentaire);
            }


            $blog = new Blog();
            $blog->setTitre($faker->text(20))
                 ->setLien($faker->url())
                 ->setSlug($this->slugger->slug(strtolower($blog->getTitre())));
            $manager->persist($blog);

            $event = new Evenement();
            $event->setTitre($faker->text(9))
                 ->setLieux($faker->word())
                 ->setDateDebut(new \DateTimeImmutable)
                 ->setDateFin(new \DateTimeImmutable);
            $manager->persist($event);
            


              

           
        }

   




        $manager->flush();
    }
}
