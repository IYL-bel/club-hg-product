<?php
/**
 * Club Hg-Product
 *
 * Add Request Testing Product form
 *
 * @package    ApplicationUsersBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\UsersBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Application\UsersBundle\Form\Type\AddRequestTestingProduct
 */
class AddRequestTestingProduct extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameProduct', 'text', array(
                'required' => false,
                'label' => 'Товар'
            ))

            ->add('phone', 'text', array(
                'required' => false,
                'label' => 'Телефон'
            ))

            ->add('commentUser', 'textarea', array(
                'required' => false,
                'label' => 'Комментарий',
            ))
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\TestProductionBundle\Entity\TestsProduction',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'add_request_testing_product_form';
    }

}
