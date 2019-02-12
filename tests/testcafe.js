import { Selector } from 'testcafe'; // first import testcafe selectors

fixture `Getting Started`// declare the fixture
    .page `127.0.0.1:8000/#/`;  // specify the start page


//then create a test and place your code there
test('Search', async t => {
    await t
        .typeText('#theLiveSearch', 'local')
        .click('.d-block')
        // Use the assertion to check if the actual header text is equal to the expected one
        .expect(Selector('#app').textContent).contains('LOCAL').expect(Selector('#theLiveSearch').textContent).eql('');
});
test('Tags', async t => {
    await t
        .click('.vs-navbar > .vs-component')
        .click(Selector('.material-icons').withText('tag'))
        .click('.vs-sidebar--background')
        .click(Selector('.btn-primary').withText('35C3'))
        // Use the assertion to check if the actual header text is equal to the expected one
        .expect(Selector('.sgfText').withText('ChaosWest').textContent).contains('ChaosWest');
});

test('Login and logout', async t => {
    await t
        .click('.vs-navbar > .vs-component')
        .click(Selector('.material-icons').withText('exit_to_app'))
        .click('.vs-sidebar--background')
        .typeText('#email', 'bla@bla.bla')
        .typeText('#password', 'blabla')
        .click('.col-md-8 > .btn-primary')
        .click('.vs-navbar > .vs-component')
        // Use the assertion to check if the actual header text is equal to the expected one
        .expect(Selector('.header-sidebar').textContent).contains('bla')
        .click(Selector('.material-icons').withText('power_settings_new'))
        .expect(Selector('.header-sidebar').textContent).notContains('bla')
        ;
});

test('Go to types', async t => {
    await t
        .click('.vs-navbar > .vs-component')
        .click('.vue-treeselect__control')
        // Use the assertion to check if the actual header text is equal to the expected one
        .expect(Selector('.vue-treeselect').textContent).contains('Audio','Video');
});