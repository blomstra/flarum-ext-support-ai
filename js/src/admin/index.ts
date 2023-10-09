import app from 'flarum/admin/app';
import User from './../common/extend/User';
import UserModal from './../common/extend/UserModal';

app.initializers.add('flarum-com-ai', () => {

    app.extensionData
        .for('flarum-com-ai')
        .registerPermission({
            icon: 'fas fa-tag',
            label: app.translator.trans('flarum-com-ai.admin.permissions.can-manage'),
            permission: 'ai.manage',
        }, 'moderate', 95)
        .registerSetting({
            setting: 'flarum-com-ai.openai-api-key',
            label: app.translator.trans('flarum-com-ai.admin.setting.api-key'),
            type: 'input',
        })
        .registerSetting({
            setting: 'flarum-com-ai.model',
            label: app.translator.trans('flarum-com-ai.admin.setting.model'),
            type: 'select',
            options: {
                'gpt-4': 'GPT-4',
                'gpt-3.5-turbo': 'GPT-3.5 Turbo',
            }
        })
        .registerSetting({
            setting: 'flarum-com-ai.openai-api-organisation',
            label: app.translator.trans('flarum-com-ai.admin.setting.api-organisation'),
            type: 'input',
        });

    // User();
    UserModal();
});
