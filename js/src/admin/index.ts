import app from 'flarum/admin/app';

app.initializers.add('blomstra-support-ai', () => {

    app.extensionData
        .for('blomstra-support-ai')
        .registerSetting({
            setting: 'blomstra-support-ai.openai-api-key',
            label: app.translator.trans('blomstra-support-ai.admin.api-key'),
            type: 'input',
        })
        .registerSetting({
            setting: 'blomstra-support-ai.openai-api-organisation',
            label: app.translator.trans('blomstra-support-ai.admin.api-organisation'),
            type: 'input',
        })
        .registerSetting({
            setting: 'blomstra-support-ai.username',
            label: app.translator.trans('blomstra-support-ai.admin.username'),
            type: 'input',
        })
        .registerSetting({
            setting: 'blomstra-support-ai.persona',
            label: app.translator.trans('blomstra-support-ai.admin.persona'),
            type: 'textarea',
        })
});
