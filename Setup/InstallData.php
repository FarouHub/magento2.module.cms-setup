<?php

namespace Lightweight\CmsSetup\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreRepository;
use Magento\Cms\Model\BlockFactory;

class InstallData implements InstallDataInterface
{

    protected $_storeRepository;
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

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface   $context
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->createTaxNoticeBlocks();
    }

    public function createTaxNoticeBlocks()
    {
        $blocks = [
            'tax_notice'          => [
                'title'     => 'Hinweis MwSt. auf Produktdetailseite',
                'is_active' => 1,
                'content'   => '<p>Inkl. MwSt.<br>zzgl.&nbsp;{{widget type="Magento\Cms\Block\Widget\Page\Link" anchor_text="Versand" title="Versand" template="Lightweight_LightweightCmsWidgets::lightweight/cmswidgets/link_inline_target_blank.phtml" page_id="4"}}</p>',
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
