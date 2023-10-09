import { extend } from 'flarum/common/extend';
import EditUserModal from 'flarum/common/components/EditUserModal';
import Stream from 'flarum/common/utils/Stream';
import Switch from 'flarum/common/components/Switch';

export default function () {
    extend(EditUserModal.prototype, 'oninit', function () {
        if (!app.user().canManageAi()) return;

        this.aiEnabled = Stream(this.attrs.user?.aiAgent());
        // this.aiBlocksAccountUse = Stream(this.attrs.user.aiBlocksAccountUse());
        // this.aiPersona = Stream(this.attrs.user.aiPersona());
        // this.aiAllowesTags = Stream(this.attrs.user.aiAllowesTags());
        // this.aiIgnoresTags = Stream(this.attrs.user.aiIgnoresTags());
        // this.aiAllowesGroups = Stream(this.attrs.user.aiAllowesGroups());
        // this.aiIgnoresGroups = Stream(this.attrs.user.aiIgnoresGroups());
        // this.aiRespondsToMentions = Stream(this.attrs.user.aiRespondsToMentions());
    });

    extend(EditUserModal.prototype, 'fields', function (items) {
        if (!app.user().canManageAi()) return;

        let trans = this.aiEnabled()
            ? 'flarum-com-ai.common.edit-user-modal.disable-button-label'
            : 'flarum-com-ai.common.edit-user-modal.enable-button-label';

        items.add(
            'ai',
            <Switch state={this.aiEnabled}>
                {app.translator.trans(trans)}
            </Switch>
        );
    });


    //
    // extend(EditUserModal.prototype, 'data', function (data) {
    //     if (!app.user().canManageAi()) return;
    //
    //     data.aiEnabled = this.aiEnabled();
    //     data.aiBlocksAccountUse = this.aiBlocksAccountUse();
    //     data.aiPersona = this.aiPersona();
    //     data.aiAllowesTags = this.aiAllowesTags();
    //     data.aiIgnoresTags = this.aiIgnoresTags();
    //     data.aiAllowesGroups = this.aiAllowesGroups();
    //     data.aiIgnoresGroups = this.aiIgnoresGroups();
    //     data.aiRespondsToMentions = this.aiRespondsToMentions();
    // });
}
