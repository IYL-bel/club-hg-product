<?php
/**
 * Club Hg-Product
 *
 * Edit Status form
 *
 * @package    TemplatesBundle
 * @author     Yury Istomenok <iyl@tut.by>
 * @copyright  2015 IYL
 */
namespace TemplatesBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


/**
 * TemplatesBundle\Form\Type\EditStatus
 */
class EditStatus extends AbstractType
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
                'label' => 'Описание статуса',
                'attr' => array(
                    'style' => 'height: 150px;',
                )
            ))

            ->add('scores','text', array(
                'required' => false,
                'label' => 'Количество баллов'
            ))
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TemplatesBundle\Entity\Statuses',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'edit_status_form';
    }

}
