<?php
/**
 * Club Hg-Product
 *
 * Add Comment Production form
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
 * Application\UsersBundle\Form\Type\AddCommentProduction
 */
class AddCommentProduction extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nameProduct', 'genemu_jqueryautocompleter_choice', array(
                'route_name' => 'hg_product_auto_complete_get_product',
                'required' => false,
                'label' => '',
                'attr' => array(
                    'class' => 'product_name',
                    'placeholder' => 'Название товара из каталога'
                ),
            ))

            ->add('description', 'textarea', array(
                'required' => false,
                'label' => '',
                'attr' => array(
                    'class' => 'form_txta',
                    'placeholder' => 'Введите текст'
                ),
            ))
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Application\UsersBundle\Entity\CommentsProduction',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'add_comment_production_form';
    }

}
