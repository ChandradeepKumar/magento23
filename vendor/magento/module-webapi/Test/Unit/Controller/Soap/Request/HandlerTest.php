<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Webapi\Test\Unit\Controller\Soap\Request;

use Magento\Framework\Api\SimpleDataObjectConverter;
use Magento\Webapi\Model\ServiceMetadata;

/**
 * Test for \Magento\Webapi\Controller\Soap\Request\Handler.
 */
class HandlerTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Magento\Webapi\Controller\Soap\Request\Handler */
    protected $_handler;

    /** @var \Magento\Framework\ObjectManagerInterface */
    protected $_objectManagerMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $_apiConfigMock;

    /** @var \Magento\Framework\Webapi\Request */
    protected $_requestMock;

    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $_authorizationMock;

    /** @var SimpleDataObjectConverter|\PHPUnit_Framework_MockObject_MockObject */
    protected $_dataObjectConverter;

    /** @var \Magento\Framework\Webapi\ServiceInputProcessor|\PHPUnit_Framework_MockObject_MockObject */
    protected $_serviceInputProcessorMock;

    /** @var \Magento\Framework\Reflection\DataObjectProcessor|\PHPUnit_Framework_MockObject_MockObject */
    protected $_dataObjectProcessorMock;

    /** @var \Magento\Framework\Reflection\MethodsMap|\PHPUnit_Framework_MockObject_MockObject */
    protected $_methodsMapProcessorMock;

    /** @var array */
    protected $_arguments;

    protected function setUp()
    {
        /** Prepare mocks for SUT constructor. */
        $this->_apiConfigMock = $this->getMockBuilder(\Magento\Webapi\Model\Soap\Config::class)
            ->setMethods(['getServiceMethodInfo'])->disableOriginalConstructor()->getMock();
        $this->_requestMock = $this->createMock(\Magento\Framework\Webapi\Request::class);
        $this->_objectManagerMock = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);
        $this->_authorizationMock = $this->createMock(\Magento\Framework\Webapi\Authorization::class);
        $this->_dataObjectConverter = $this->createPartialMock(
            \Magento\Framework\Api\SimpleDataObjectConverter::class,
            ['convertStdObjectToArray']
        );
        $this->_serviceInputProcessorMock = $this->createMock(\Magento\Framework\Webapi\ServiceInputProcessor::class);
        $this->_dataObjectProcessorMock = $this->createMock(\Magento\Framework\Reflection\DataObjectProcessor::class);
        $this->_methodsMapProcessorMock = $this->createMock(\Magento\Framework\Reflection\MethodsMap::class);

        /** Initialize SUT. */
        $this->_handler = new \Magento\Webapi\Controller\Soap\Request\Handler(
            $this->_requestMock,
            $this->_objectManagerMock,
            $this->_apiConfigMock,
            $this->_authorizationMock,
            $this->_dataObjectConverter,
            $this->_serviceInputProcessorMock,
            $this->_dataObjectProcessorMock,
            $this->_methodsMapProcessorMock
        );
        parent::setUp();
    }

    public function testCall()
    {
        $requestedServices = ['requestedServices'];
        $this->_requestMock->expects($this->once())
            ->method('getRequestedServices')
            ->will($this->returnValue($requestedServices));
        $this->_dataObjectConverter->expects($this->once())
            ->method('convertStdObjectToArray')
            ->will($this->returnValue(['field' => 1]));
        $this->_methodsMapProcessorMock->method('getMethodReturnType')->willReturn('string');
        $operationName = 'soapOperation';
        $className = \Magento\Framework\DataObject::class;
        $methodName = 'testMethod';
        $isSecure = false;
        $aclResources = [['Magento_TestModule::resourceA']];
        $this->_apiConfigMock->expects($this->once())
            ->method('getServiceMethodInfo')
            ->with($operationName, $requestedServices)
            ->will(
                $this->returnValue(
                    [
                        ServiceMetadata::KEY_CLASS => $className,
                        ServiceMetadata::KEY_METHOD => $methodName,
                        ServiceMetadata::KEY_IS_SECURE => $isSecure,
                        ServiceMetadata::KEY_ACL_RESOURCES => $aclResources,
                    ]
                )
            );

        $this->_authorizationMock->expects($this->once())->method('isAllowed')->will($this->returnValue(true));
        $serviceMock = $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->setMethods([$methodName])
            ->getMock();

        $serviceResponse = ['foo' => 'bar'];
        $serviceMock->expects($this->once())->method($methodName)->will($this->returnValue($serviceResponse));
        $this->_objectManagerMock->expects($this->once())->method('get')->with($className)
            ->will($this->returnValue($serviceMock));
        $this->_serviceInputProcessorMock
            ->expects($this->once())
            ->method('process')
            ->will($this->returnArgument(2));

        /** Execute SUT. */
        $this->assertEquals(
            ['result' => $serviceResponse],
            $this->_handler->__call($operationName, [(object)['field' => 1]])
        );
    }
}
