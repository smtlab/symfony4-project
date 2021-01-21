<?php
declare(strict_types=1);
namespace App\Command;

use App\Service\PaymentService;
use App\Repository\ProductRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PaymentCommand extends Command
{
    protected static $defaultName = 'create-payment';

    /** @var PaymentService $paymentService */
    private $paymentService;

    /** @var ValidatorInterface $validator */
    private $validator;

    /** @var ProductRepository $productRepository */
    private $productRepository;

    public function __construct(
        PaymentService $paymentService,
        ValidatorInterface $validator,
        ProductRepository $productRepository
    ) {
        parent::__construct();
        $this->paymentService = $paymentService;
        $this->validator = $validator;
        $this->productRepository = $productRepository;
    }

    /** {@inheritDoc} */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates a new payment for product.')
            ->setHelp('This command allows you to pay for a product...')
            ->addArgument('paymentMethod', InputArgument::REQUIRED, 'Payment method: paypal, razorpay')
            ->addArgument('productName', InputArgument::REQUIRED, 'Product name')
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
        $product = $this->productRepository->findOneBy(['name' => $input->getArgument('productName')]);
        if (!$product) {
            $output->writeln('<error>Product not found: ' . $input->getArgument('productName') . '</error>');
            return 1;
        }
        $this->paymentService->create($input->getArgument('paymentMethod'), $product);
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
        $paymentMethod = new Choice(['paypal', 'razorpay']);
        $paymentMethod->message = 'Allowed payment methods: paypal, razorpay';

        $productName = new Regex('/[a-zA_Z0-9\s]/');
        $productName->message = 'Only alphabets, numbers and spaces are allowed in product name';

        $constraint = new Collection([
            'command' => null,
            'paymentMethod' => $paymentMethod,
            'productName' => $productName
        ]);

        return $this->validator->validate($arguements, $constraint);
    }
}
