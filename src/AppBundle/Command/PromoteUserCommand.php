<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use AppBundle\Entity\User;

class PromoteUserCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('user:promote')
            ->setDefinition(array(
                new InputArgument('email', InputArgument::REQUIRED, 'The user email'),
                new InputArgument('role', InputArgument::REQUIRED, 'The role'),
            ))
            ->setDescription('Promotes a user by adding a role')
            ->setHelp(<<<EOT
The <info>user:promote</info> command promotes a user by adding a role
<info>php app/console user:promote contact@mysite.fr ROLE_CUSTOM</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $role = $input->getArgument('role');

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var $user User */
        $user = $em->getRepository('AppBundle:User')->findOneBy(array(
            'username' => $email));

        if (!$user) {
            throw new \InvalidArgumentException(sprintf('User identified by "%s" email does not exist.', $email));
        }

        $user->addRole($role);
        $em->persist($user);
        $em->flush();

        $output->writeln(sprintf('Role "%s" has been added to user with email "%s".', $role, $email));
    }
}
