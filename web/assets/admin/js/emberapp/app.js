// Generated by CoffeeScript 1.7.1
(function() {
  var PulpyAdmin;

  Ember.LOG_BINDINGS = true;

  PulpyAdmin = Ember.Application.create({
    LOG_TRANSITIONS: true,
    LOG_TRANSITIONS_INTERNAL: true,
    LOG_ACTIVE_GENERATION: true,
    LOG_VIEW_LOOKUPS: true
  });

  DS.RESTAdapter.reopen({
    namespace: 'api'
  });

  PulpyAdmin.Router.map(function() {
    this.resource('posts', function() {
      return this.route('view', {
        path: '/view/:post_id'
      });
    });
    this.route('newpost');
    return this.route('settings');
  });

  PulpyAdmin.PostsRoute = Ember.Route.extend({
    model: function() {
      return this.store.findAll('post');
    }
  });

  PulpyAdmin.PostsViewController = Ember.ObjectController.extend({
    htmlbody: (function() {
      console.log('ICIII - ' + new upndown().convert(this.get('model.content')));
      return marked(this.get('model.content'));
    }).property('model.content')
  });

  PulpyAdmin.Post = DS.Model.extend({
    title: DS.attr('string'),
    intro: DS.attr('string'),
    content: DS.attr('string'),
    author: DS.belongsTo('appuser')
  });

  PulpyAdmin.Appuser = DS.Model.extend({
    email: DS.attr('string'),
    website: DS.attr('string'),
    bio: DS.attr('string'),
    twitter: DS.attr('string')
  });

  PulpyAdmin.PostsView = Ember.View.extend({
    classNames: ['posts-view']
  });

  PulpyAdmin.ApplicationView = Ember.View.extend({
    classNames: ['application-view']
  });

  PulpyAdmin.ActivableLiComponent = Ember.Component.extend({
    tagName: 'li',
    classNameBindings: ['active'],
    active: (function() {
      return this.get('childViews').anyBy('active');
    }).property('childViews.@each.active')
  });

}).call(this);
