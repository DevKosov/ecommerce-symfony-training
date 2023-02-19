<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class UserType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => $this->translator->trans('signup.email.label'),
                'attr' => [
                    'placeholder' => $this->translator->trans('signup.email.placeholder'),
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('signup.email.required'),
                    ]),
                    new Email([
                        'message' => $this->translator->trans('signup.email.invalid'),
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => $this->translator->trans('signup.password.label'),
                'help' => $this->translator->trans('signup.password.help'),
                'attr' => [
                    'class' => 'form-text',
                    'aria-describedby' => 'nom-help',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('signup.password.required'),
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => $this->translator->trans('signup.password.length'),
                    ]),
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => $this->translator->trans('signup.nom.label'),
                'constraints' => [
                    new NotBlank([
                        'message' => "<small>" . $this->translator->trans('signup.nom.required') . "</small>",
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => $this->translator->trans('signup.nom.length'),
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => $this->translator->trans('signup.prenom.label'),
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('signup.prenom.required'),
                    ]),
                    new Length([
                        'max' => 255,
                        'maxMessage' => $this->translator->trans('signup.prenom.length'),
                    ]),
                ],
            ])
            ->add('captcha', CaptchaType::class, array(
                'width' => 200,
                'height' => 50,
                'length' => 6,
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
