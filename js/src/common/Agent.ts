import Model from 'flarum/common/Model';
import type Group from 'flarum/common/models/Group';
import type User from 'flarum/common/models/User';
import type Tag from 'flarum/tags/models/Tag';

export default class Agent extends Model {
    blocksAccountUse() {
        return Model.attribute<boolean>('blocksAccountUse').call(this);
    }
    persona() {
        return Model.attribute<string | null>('persona').call(this);
    }
    allowedTags() {
        return Model.hasMany<Tag>('allowedTags').call(this);
    }
    ignoredTags() {
        return Model.hasMany<Tag>('ignoredTags').call(this);
    }
    allowedGroups() {
        return Model.hasMany<Group>('allowedGroups').call(this);
    }
    ignoredGroups() {
        return Model.hasMany<Group>('ignoredGroups').call(this);
    }
    respondsToMentions() {
        return Model.attribute<boolean>('respondsToMentions').call(this);
    }
    user() {
        return Model.hasOne<User>('user').call(this);
    }
}
