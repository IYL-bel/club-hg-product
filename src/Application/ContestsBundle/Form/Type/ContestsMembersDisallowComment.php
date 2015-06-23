<?php
/**
 * Club Hg-Product
 *
 * Contests Members Disallow Comment form
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
 * Application\ContestsBundle\Form\Type\ContestsMembersDisallowComment
 */
class ContestsMembersDisallowComment extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commentAdmin', 'textarea', array(
                'required' => false,
                'label' => ' Комментарий',
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
            'validation_groups' => array('form-disallow'),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'contests_members_disallow_comment_form';
    }

}
