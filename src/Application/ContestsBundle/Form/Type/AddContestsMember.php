<?php
/**
 * Club Hg-Product
 *
 * Add Contests Member form
 *
 * @package    ApplicationContestsBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace Application\ContestsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * Application\ContestsBundle\Form\Type\AddContestsMember
 */
class AddContestsMember extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            'data_class' => 'Application\ContestsBundle\Entity\ContestsMembers',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'add_contests_member_form';
    }

}
