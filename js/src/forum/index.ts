import app from 'flarum/admin/app';
import User from './../common/extend/User';
import UserModal from './../common/extend/UserModal';

app.initializers.add('flarum-com-ai', () => {
    User();
    UserModal();
});
