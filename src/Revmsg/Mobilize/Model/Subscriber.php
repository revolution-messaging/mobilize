<?php
namespace Revmsg\Mobilize\Model;

class Subscriber extends Scheme
{
    protected $options  =   array();
    protected $vars     =   array(
        'id'            =>  null,
        'blacklist'     =>  array(),
        'mobilePhoneNo'     =>  null,
        'subscriberMetaData'    =>  array(),
        'listDetails'       =>  array(),
        'listId'        =>  null
        );
    protected $APIModels = array(
        'MetadataAPIModel' => array(),
        'SubscribersAndList' => array()
        );
}
