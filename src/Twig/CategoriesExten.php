<?php
namespace App\Twig;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CategoriesExten extends AbstractExtension
{

    private $em;

    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    public function getFunction(): array
    {

        return [ new TwigFunction('cat',[$this, 'getCategories'])];
    }

    public function getCategories(){

        return $this->em->getRepository(Categories::class)->findcatygorieparent();
    }
}

