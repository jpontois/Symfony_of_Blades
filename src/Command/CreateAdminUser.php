<?php

namespace App\Command;

use App\Entity\UserEntity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminUser extends Command
{
    protected static $defaultName = 'app:createAdminUser';

    private $entityManager;
    private $passwordEncoder;

    public function __construct(
        string $name = null,
        EntityManagerInterface $entityManager,
        UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setDescription('This command create an admin user')
            ->addArgument('email', InputArgument::REQUIRED, 'Admin user email')
            ->addArgument('password', InputArgument::REQUIRED, 'Admin user password')
            ->addArgument('firstName', InputArgument::OPTIONAL, 'First Name')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'Last Name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'Confirmer la création de l\'utilisateur?',
            false, '/^(y|j)/i');

        if (!$helper->ask($input, $output, $question)) {
            return 0;
        }

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');

        $io->note(sprintf('User email: %s', $email));
        $io->note(sprintf('User password: %s', $password));
        $io->note(sprintf('User First Name: %s', $firstName ?? ''));
        $io->note(sprintf('User Last Name: %s', $lastName ?? ''));

        $user = new UserEntity();
        $hashedPassword = $this->passwordEncoder->encodePassword($user, $password);
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setFirstName($firstName ?? '');
        $user->setLastName($lastName ?? '');
        $user->setCreationDate(new \DateTime());

        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception) {
            $io->error('A error occured : ' . $exception->getMessage());

            return 0;
        }

        $io->success('A new user has been created');

        return 0;
    }
}
