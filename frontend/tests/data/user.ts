import {User} from "../../src/stores/user/types";

export function initUsers(): User[] {
    return [
        {id: 1, email: 'aaa', username: 'duck3000', roles: ['role1']},
        {id: 2, email: 'ccc', username: 'bat4000', roles: ['role3']},
        {id: 3, email: 'bbb', username: 'dindon37', roles: ['role2']},
    ]
}
