<?php
declare(strict_types=1);
namespace App\Command;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ProductCreatorCommand extends Command
{
    protected static $defaultName = 'create-product';

    /** @var EntityManagerInterface $em */
    private $em;

    /** @var ValidatorInterface $validator */
    private $validator;

    public function __construct(
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ) {
        parent::__construct();
        $this->em = $em;
        $this->validator = $validator;
    }

    /** {@inheritdoc} */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new product.')
            ->setHelp('This command allows you to create new product...')
            ->addArgument('name', InputArgument::REQUIRED, 'Product name')
            ->addArgument('price', InputArgument::REQUIRED, 'Product name')
        ;
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $errors = $this->validate($input->getArguments());
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $output->writeln('<error>' . $error->getMessage() . '</error>');
            }
            return 1;
        }

        $product = new Product();
        $product->setName($input->getArgument('name'));
        $product->setPrice($input->getArgument('price'));
        $this->em->persist($product);
        $this->em->flush();
        return 0;
    }

    /**
     * Validate command arguments
     *
     * @param array $arguements
     * @return ConstraintViolationListInterface
     */
    private function validate(array $arguements): ConstraintViolationListInterface
    {
        $priceConstraint = new Positive();
        $priceConstraint->message = 'Product price must be a valid number';

        $productName = new Regex('/[a-zA_Z0-9\s]/');
        $productName->message = 'Only alphabets, numbers and spaces are allowed in product name';

        $constraint = new Collection([
            'command' => null,
            'name' => $productName,
            'price' => $priceConstraint
        ]);

        return $this->validator->validate($arguements, $constraint);
    }
}
