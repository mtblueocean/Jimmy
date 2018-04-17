<?php

namespace JimmyBase\Service;

use Zend\Authentication\AuthenticationService;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceManagerAwareInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Crypt\Password\Bcrypt;

use Zend\View\Model\ViewModel;
use Aws\Ses\SesClient;
use SimpleEmailServiceMessage;
use SimpleEmailService;

use ZfcBase\EventManager\EventProvider;
use JimmyBase\Mapper\ReportShareInterface as ReportShareMapperInterface;

class ReportShare extends EventProvider   implements ServiceManagerAwareInterface
{
    /**
     * @var ReportShareMapperInterface
     */
    protected $reportshareMapper;


    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    public function send($reportShareData, $email)
    {
        try{
            $viewModel  = new ViewModel();

            $viewModel->setVariable('report' , $reportShareData['report'])
                      ->setVariable('agency',$reportShareData['agency'])
                      ->setVariable('user',$reportShareData['user'])
                      ->setTemplate('jimmy-base/emails/report-share.phtml');

            $htmlOutput = $this->getServiceManager()->get('viewrenderer')
                                                    ->render($viewModel);

            $subject = $reportShareData['agency']->getName() ? $reportShareData['agency']->getName() : $reportShareData['agency']->getEmail() ;
            $subject.= " shared a report with you";

            $from_name = "JimmyData";
            $from_email = "no-reply@jimmydata.com";

            $recipents = preg_split( "/(,|;)/", $email );

            //dan-zahariev mailer
            $m = new SimpleEmailServiceMessage();
            $m->addTo($recipents);
            $m->setFrom($from_name.' <no-reply@jimmydata.com>');
            $m->setSubject($subject);
            $m->setMessageFromString('',$htmlOutput);
            $ses = new SimpleEmailService('AKIAJ6JNDERKIJ6ZZRUA', 'ScAFz01A740UHY/1d+JcJzzFUdekpfBQjNdken10', SimpleEmailService::AWS_EU_WEST1);

            $ses->enableVerifyHost(false);
            $ses->enableVerifyPeer(false);
            $result = $ses->sendEmail($m, $use_raw_request=true);

            return true;
        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    public function save(array $data)
    {
		 //try{	
			$reportshare  = new \JimmyBase\Entity\ReportShare();
			$reportshare->setUserId($data['user_id']);
            $reportshare->setReportId($data['report_id']);
            $reportshare->setStatus(1);
            $reportshare->setDate(date('Y-m-d h:i:s'));
				
			$this->getEventManager()->trigger(__FUNCTION__, $this, array('reportshare' => $reportshare, 'form' => $form));
			
			$this->getMapper()->insert($reportshare);
	
			$this->getEventManager()->trigger(__FUNCTION__.'.post', $this, array('report' => $reportshare, 'form' => $form));
			
			return $reportshare;
		 //} catch(Exception $e){
		//	 print_r($e);
		 
		// }
    }
	
    /**
     * getUserMapper
     *
     * @return ReportShareMapperInterface
     */
    public function getMapper()
    {
        if (null === $this->reportshareMapper) {
            $this->reportshareMapper = $this->getServiceManager()->get('jimmybase_reportshare_mapper');
        }
        return $this->reportshareMapper;
    }

    /**
     * setUserMapper
     *
     * @param ReportShareMapperInterface $userMapper
     * @return User
     */
    public function setMapper(ReportShareMapperInterface $reportMapper)
    {
        $this->reportshareMapper = $reportshareMapper;
        return $this;
    }

    /**
     * Retrieve service manager instance
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set service manager instance
     *
     * @param ServiceManager $serviceManager
     * @return User
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }
}
