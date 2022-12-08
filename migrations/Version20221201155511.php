<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20221201155511
 * @package DoctrineMigrations
 */
final class Version20221201155511 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, girl_one_id INT NOT NULL, girl_two_id INT NOT NULL, girl_winner_id INT DEFAULT NULL, created_at INT DEFAULT 0 NOT NULL, updated_at INT DEFAULT 0 NOT NULL, INDEX IDX_5A108564F94EB5A4 (girl_one_id), INDEX IDX_5A1085649212526B (girl_two_id), INDEX IDX_5A108564D0988ADF (girl_winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564F94EB5A4 FOREIGN KEY (girl_one_id) REFERENCES girl (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A1085649212526B FOREIGN KEY (girl_two_id) REFERENCES girl (id)');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564D0988ADF FOREIGN KEY (girl_winner_id) REFERENCES girl (id)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564F94EB5A4');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A1085649212526B');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A108564D0988ADF');
        $this->addSql('DROP TABLE vote');
    }
}
