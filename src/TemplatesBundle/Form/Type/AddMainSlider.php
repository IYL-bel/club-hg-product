<?php
/**
 * Club Hg-Product
 *
 * Add Main Slider form
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
 * TemplatesBundle\Form\Type\AddMainSlider
 */
class AddMainSlider extends AbstractType
{

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', 'textarea', array(
                'required' => false,
                'label' => 'Текст на слайде',
            ))

            ->add('textColor', 'choice', array(
                'choices' => array(
                    'black' => 'Черный',
                    'white' => 'Белый'
                ),
                'required' => false,
                'label' => 'Цвет текста',
                'empty_value' => ''
            ))

            ->add('link','text', array(
                'required' => false,
                'label' => 'Ссылка'
            ))

            ->add('pictureFile', 'file', array(
                'required' => false,
                'label' => 'Фоновая картинка (формат PNG, размер 558x411)'
            ))

            ->add('picturePath', 'hidden')
        ;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TemplatesBundle\Entity\MainSlider',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'add_main_slider_form';
    }

}
