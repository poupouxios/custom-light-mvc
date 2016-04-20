class CreateSeedMigration < ActiveRecord::Migration
  def self.up
    create_table :seed_migrations, options: 'DEFAULT CHARSET=utf8' do |t|
      t.string :class_name
      t.timestamps
    end
  end

  def self.down
    drop_table :seed_migrations
  end

end
