<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),

            // additional bundles
            new FOS\UserBundle\FOSUserBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),

            // my bundles
            new Application\AdminBundle\ApplicationAdminBundle(),
            new Application\BaseBundle\ApplicationBaseBundle(),
            new Application\ContestsBundle\ApplicationContestsBundle(),
            new Application\MessageBundle\ApplicationMessageBundle(),
            new Application\PrizesBundle\ApplicationPrizesBundle(),
            new Application\ScoresBundle\ApplicationScoresBundle(),
            new Application\TestProductionBundle\ApplicationTestProductionBundle(),
            new Application\UsersBundle\ApplicationUsersBundle(),
            new HgProductBundle\HgProductBundle(),
            new SocialNetworksBundle\SocialNetworksBundle(),
            new TemplatesBundle\TemplatesBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
