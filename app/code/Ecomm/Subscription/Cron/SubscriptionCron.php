<?php

namespace Ecomm\Subscription\Cron;

use \Psr\Log\LoggerInterface;
use Ecomm\Subscription\Api\SubscriptionCronRepositoryInterface;

class SubscriptionCron
{
    protected $logger;
    protected $subscriptionCronRepositoryInterface;

    public function __construct(
        LoggerInterface $logger,
        SubscriptionCronRepositoryInterface $subscriptionCronRepositoryInterface
    ) {

        $this->logger = $logger;
        $this->subscriptionCronRepositoryInterface = $subscriptionCronRepositoryInterface;
    }

    /**
     * Get Discount Price
     *
     * @return array
     */
    public function execute()
    {
        $runnerData = $this->subscriptionCronRepositoryInterface->getCronFilter([1]);
        $this->logger->info('Date '. date('Y-m-d'));
        $this->logger->info('working1 '. json_encode($runnerData->getData()));
        if (count($runnerData) > 0) {
            foreach ($runnerData as $lodder) {
                $stopService = $this->getEnd($lodder);
                $report = $this->cronOrderCreater($stopService);
                
                // $this->logger->info('working2 '. json_encode($stopService->getData()));
                // $this->subscriptionCronRepositoryInterface->save($stopService);
            }
        } else {
            $this->logger->info('Data Null ');
        }
      //  $this->subscriptionCronRepositoryInterface->getCronFilter([1]);
     
     }

     private function cronOrderCreater()
     {

     }

     private function getEnd($loadder):array
     {
        $data = [];
        $status = 'Not_End';
        switch ($loadder->getSubscriptionEndType()) {
            case 'Date':
                if ($loadder->getSubscriptionEndValue() == date('Y-m-d')) {
                    $loadder->setStatus(false);
                    $status = 'End';
                }
                return ['status'=>$status,'value'=>$loadder];
            case 'Cycle':
                if ($loadder->getSubscriptionEndValue() == 1) {
                    $loadder->setSubscriptionEndValue(0);
                    $loadder->setStatus(false);
                    $status = 'End';
                } else {
                    $loadder->setSubscriptionEndValue($loadder->getSubscriptionEndValue()-1);
                }
                return ['status'=>$status,'value'=>$loadder];
            case 'Until':
                if ($loadder->getSubscriptionEndValue() == 'Yes') {
                    $loadder->setStatus(false);
                    $status = 'End';
                }
                return ['status'=>$status,'value'=>$loadder];
            default:
                throw new InputException(__('Something Went Wrong'));
        }
     }
}
