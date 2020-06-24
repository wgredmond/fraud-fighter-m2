<?php

 namespace WGRedmond\FraudFighter\Controller\Index;


 use Magento\Framework\App\Action\Action;
 use Magento\Framework\App\Action\Context;
 use Magento\Framework\View\Result\PageFactory;
 use \Magento\Framework\Json\Helper\Data;

 class getCustomerDecision extends Action
  {
	protected $resultPageFactory;
	protected $jsonHelper;

	public function __construct(Context $context, PageFactory $pageFactory, Data $jsonHelper )
	{
		$this->resultPageFactory = $pageFactory;
		$this->jsonHelper = $jsonHelper;
		parent::__construct($context);
	}

	public function execute()
	{
		//$post = $this->getRequest()->getPostValue();
		//echo "We are in the controller";
//		$client = new \SiftClient(array('api_key' => '9212d40acb619fd8', 'account_id' => '5d30e0454f0c3223905224df'));
//		 $responseDecision = $client->getUserDecisions("58", array('account_id' => '5d30e0454f0c3223905224df'));
//		  $encodedData = $this->jsonHelper->jsonEncode($responseDecision);
//		  $decodedData = $this->jsonHelper->jsonDecode($encodedData);
//		  print_r($decodedData);
		  //return $encodedData;

	}
}