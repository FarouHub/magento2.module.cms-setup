<?php

namespace Lightweight\CmsSetup\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreRepository;
use Magento\Cms\Model\BlockFactory;

/**
 * Class UpgradeData
 *
 * @package Lightweight\LightweightSetup\Setup
 */
class UpgradeData implements UpgradeDataInterface
{

    /** @var StoreRepository $_storeRepository */
    protected $_storeRepository;

    /** @var BlockFactory $_blockFactory */
    protected $_blockFactory;

    /**
     * InstallData constructor.
     *
     * @param StoreRepository $storeRepository
     * @param BlockFactory    $blockFactory
     */
    public function __construct(
        StoreRepository $storeRepository,
        BlockFactory $blockFactory
    )
    {
        $this->_storeRepository = $storeRepository;
        $this->_blockFactory = $blockFactory;

    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->createEmailFooterBlock();
        }
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $this->createStaticBlocksForFooter();
            $this->updateStaticBlocksForFooter();
        }
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->createStaticBlockHandcrafted();
        }
    }

    public function createEmailFooterBlock()
    {
        $blocks = [
            'email_footer'          => [
                'title'     => 'Email Footer',
                'is_active' => 1,
                'content'   => '<table>
                                    <tbody>
                                        <tr>
                                            <td>Adresse</td>
                                            <td>Geschäftszeiten</td>
                                        </tr>
                                        <tr>
                                            <td>carbovation gmbh<br>Otto-Lilienthal-Str. 15<br>88046 Friedrichshafen<br>Deutschland</td>
                                            <td>08:00 - 12:00 Uhr<br>13:00 - 17:00 Uhr</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Service Hotline</td>
                                            <td>Reparatur von Laufrädern</td>
                                        </tr>
                                        <tr>
                                            <td>Tel +49 (7541) 3889 - 12<br>Fax +49 (7541) 3889 - 55<br>germany@lightweight.info</td>
                                            <td>Tel +49 (7541) 3889 - 14<br>Fax +49 (7541) 3889 - 55<br>repair@lightweight.info</td>
                                        </tr>
                                    </tbody>
                                </table>',
            ],
        ];
        $stores = $this->_storeRepository->getList();

        foreach ($stores as $store) {
            if ($store->getStoreId() == 0) {
                continue;
            }
            foreach ($blocks as $identifier => $data) {
                $this->_blockFactory->create()->setData($data)
                                    ->setIdentifier($identifier)
                                    ->setStores(array($store->getStoreId()))
                                    ->save();
            }
        }
    }

    public function createStaticBlocksForFooter() {
        $blocks = [
            'footer_payment_methods' => [
                'title'     => 'Footer - Zahlungsarten',
                'is_active' => 1,
                'content'   => '<p>
<strong>Zahlungsarten</strong>
<img src="{{view url="images/logos/visa.svg"}}" alt="Visa" />
<img src="{{view url="images/logos/paypal.svg"}}" alt="PayPal" />
<img src="{{view url="images/logos/mastercard.svg"}}" alt="Mastercard" />
<img src="{{view url="images/logos/finanzierung.png"}}" alt="Klarna Finanzierung" />
</p>'
            ],
            'footer_shipping_methods' => [
                'title'     => 'Footer - Versandarten',
                'is_active' => 1,
                'content'   => '<p>
<strong>Versand</strong>
<img src="{{view url="images/logos/ups.svg"}}" alt="UPS" />
</p>'
            ],
            'footer_information' => [
                'title'     => 'Footer - Informationen',
                'is_active' => 1,
                'content'   => '<p>
<strong>Informationen</strong>
{{widget type="Magento\Cms\Block\Widget\Page\Link" anchor_text="Über Lightweight" title="Über Lightweight" template="widget/link/link_inline.phtml" page_id="2"}}
{{widget type="Magento\Cms\Block\Widget\Page\Link" anchor_text="Impressum" title="Impressum" template="widget/link/link_inline.phtml" page_id="2"}}
{{widget type="Magento\Cms\Block\Widget\Page\Link" anchor_text="Datenschutz" title="Datenschutz" template="widget/link/link_inline.phtml" page_id="2"}}
{{widget type="Magento\Cms\Block\Widget\Page\Link" anchor_text="AGB" title="AGB" template="widget/link/link_inline.phtml" page_id="2"}}
</p>'
            ],
            'footer_social' => [
                'title'     => 'Footer - Folgen Sie uns',
                'is_active' => 1,
                'content'   => '<p>
<strong>Folgen Sie uns</strong>
<a href="http://www.facebook.com/LightweightCS" target="_blank" title="Facebook"><img src="{{view url="images/logos/facebook.svg"}}" alt="Facebook" /></a>
<a href="http://www.instagram.com/ridelightweight" target="_blank" title="Instagram"><img src="{{view url="images/logos/instagram.svg"}}" alt="Instagram" /></a>
<a href="http://www.youtube.com/user/LightweightWheels" target="_blank" title="YouTube"><img src="{{view url="images/logos/youtube.svg"}}" alt="YouTube" /></a>
<a href="http://www.twitter.com/ridelightweight" target="_blank" title="Twitter"><img src="{{view url="images/logos/twitter.svg"}}" alt="Twitter" /></a>
</p>'
            ],
        ];

        $stores = $this->_storeRepository->getList();

        foreach ($stores as $store) {
            if ($store->getStoreId() == 0) {
                continue;
            }
            foreach ($blocks as $identifier => $data) {
                $this->_blockFactory->create()->setData($data)
                                    ->setIdentifier($identifier)
                                    ->setStores(array($store->getStoreId()))
                                    ->save();
            }
        }
    }

    public function updateStaticBlocksForFooter() {
        $blocks = [
            'footer_site_logo'          => [
                'content'   => '<p><img src="{{view url="images/logos/lw_footer_logo_gray_full.png"}}" width="110"></p>',
            ],
        ];

        $stores = $this->_storeRepository->getList();

        foreach ($stores as $store) {
            if ($store->getStoreId() == 0) {
                continue;
            }
            foreach ($blocks as $identifier => $data) {
                $blockModel = $this->_blockFactory->create()->setStoreId($store->getId());
                $blockModel->load($identifier, 'identifier');
                if($blockModel->getId()) {
                    $blockModel->setContent($data['content'])
                        ->save();
                }
            }
        }
    }

    public function createStaticBlockHandcrafted() {
        $blocks = [
            'product_handcrafted' => [
                'title'     => 'Produktseite - Handgefertigt',
                'is_active' => 1,
                'content'   => '<p>Handgefertigt</p>'
            ],
        ];

        $stores = $this->_storeRepository->getList();

        foreach ($stores as $store) {
            if ($store->getStoreId() == 0) {
                continue;
            }
            foreach ($blocks as $identifier => $data) {
                $this->_blockFactory->create()->setData($data)
                                    ->setIdentifier($identifier)
                                    ->setStores(array($store->getStoreId()))
                                    ->save();
            }
        }
    }

}
