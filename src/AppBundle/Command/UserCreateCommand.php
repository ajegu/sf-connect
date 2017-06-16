<?php

namespace AppBundle\Command;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Create a user in database with the same value for password')
            ->addArgument('username', InputArgument::REQUIRED, 'The username is required')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');

        $user = new User();

        $encoder = $this->getContainer()->get('security.password_encoder');
        $password = $encoder->encodePassword($user, $username);

        $user->setUsername($username)
            ->setEmail($username . '@sf-connect.dev')
            ->setPassword($password);

        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($user);
        $em->flush();

        $output->writeln('User ' . $username . ' has been created!');
    }

}
