<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        $pluginSignature = str_replace('_', '', 'ak_forum') . '_akforum';
        $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue($pluginSignature, 'FILE:EXT:ak_forum/Configuration/FlexForms/flexform_akforum.xml');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('ak_forum', 'Configuration/TypoScript', 'Just a forum');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_akforum_domain_model_forum', 'EXT:ak_forum/Resources/Private/Language/locallang_csh_tx_akforum_domain_model_forum.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_akforum_domain_model_forum');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_akforum_domain_model_topic', 'EXT:ak_forum/Resources/Private/Language/locallang_csh_tx_akforum_domain_model_topic.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_akforum_domain_model_topic');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_akforum_domain_model_thema', 'EXT:ak_forum/Resources/Private/Language/locallang_csh_tx_akforum_domain_model_thema.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_akforum_domain_model_thema');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_akforum_domain_model_post', 'EXT:ak_forum/Resources/Private/Language/locallang_csh_tx_akforum_domain_model_post.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_akforum_domain_model_post');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_akforum_domain_model_reply', 'EXT:ak_forum/Resources/Private/Language/locallang_csh_tx_akforum_domain_model_reply.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_akforum_domain_model_reply');

    }
);
