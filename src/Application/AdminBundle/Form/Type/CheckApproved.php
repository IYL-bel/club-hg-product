<?php
/**
 * Club Hg-Product
 *
 * Check Approved form
 *
 * @package    ApplicationAdminBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Application\AdminBundle\Form\Type\CheckApproved
 */
class CheckApproved extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('points', 'text', array(
                'required' => false,
                'label' => 'Количество баллов',
            ))
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UsersBundle\Entity\Checks',
            'validation_groups' => array('form-approved'),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'check_approved_form';
    }

}
