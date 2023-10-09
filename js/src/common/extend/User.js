
import Model from 'flarum/common/extenders/Model';
import User from 'flarum/common/models/User';
import Agent from './../Agent';

export default function ()
{
    User.prototype.aiAgent = Model.hasOne<Agent>('aiAgent');
}
